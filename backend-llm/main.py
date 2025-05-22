from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse, StreamingResponse
from fastapi.middleware.cors import CORSMiddleware
from langdetect import detect
from intent_detector import detect_intent
import httpx
import os
import logging
import traceback
import json

# === CONFIGURATION ===
# ... imports (inchangés)
# CONFIGURATION
OLLAMA_BASE = os.getenv("OLLAMA_API", "http://127.0.0.1:11434")
OLLAMA_URL = f"{OLLAMA_BASE}/api/generate"

app = FastAPI()
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

logging.basicConfig(level=logging.INFO)
print(f"✅ OLLAMA_URL = {OLLAMA_URL}")

# === PROMPT BUILDER PAR DÉFAUT ===
def build_prompt(message: str, lang: str, history: list = None) -> str:
    instruction = {
        "fr": "Tu es BrainBot, assistant de BrainCode Startup Studio. Réponds de façon utile, directe, sans blabla ni résumé.",
        "en": "You are BrainBot from BrainCode. Respond clearly and directly without filler."
    }.get(lang, "You are BrainBot. Be brief and helpful.")

    prompt = f"{instruction}\n\n"
    if history:
        for h in history[-2:]:
            role = "Utilisateur" if h["sender"] == "user" else "Chatbot"
            prompt += f"{role} : {h['text'].strip()}\n"
    prompt += f"Utilisateur : {message.strip()}\nChatbot :"
    return prompt

# === ENDPOINT ===
@app.post("/chat-stream")
async def chat_stream(request: Request):
    try:
        data = await request.json()
        message = data.get("message", "").strip()
        history = data.get("history", [])
        lang = detect(message)
        intent = detect_intent(message)
        logging.info(f"Intent détectée : {intent}")

        # 🎯 Intent connu
        if intent == "liste_etapes_programme":
            return JSONResponse({
                "response": "Voici les 12 étapes de l'incubation d'une startup :\n"
                           "1. Idée & validation\n2. Comité de pilotage\n3. Étude de marché\n4. Business model\n"
                           "5. Plan stratégique\n6. Pitch deck\n7. Prototype\n8. Processus opérationnels\n"
                           "9. Recrutement\n10. MVP\n11. Feedback\n12. Scaling",
                "lang": lang
            })

        elif intent == "objectif_du_programme":
            prompt = (
                "Tu es un assistant d’incubation. Résume en 2 phrases l’objectif de BrainCode Startup Studio : "
                "aider les startups à transformer une idée en produit via 12 étapes structurées avec coaching et livrables.\n\n"
                f"Utilisateur : {message.strip()}\nChatbot :"
            )

        elif intent == "livrable":
            prompt = (
                "Réponds uniquement à la question sur le livrable d’une étape BrainCode, sans reformulation.\n\n"
                f"Utilisateur : {message.strip()}\nChatbot :"
            )

        elif intent == "retard":
            prompt = (
                "Un utilisateur est en retard. Explique comment demander un délai ou régulariser son étape dans BrainCode.\n\n"
                f"Utilisateur : {message.strip()}\nChatbot :"
            )

        elif intent == "prise_de_rdv":
            prompt = (
                "L’utilisateur souhaite réserver un rendez-vous avec son coach. Explique la démarche dans BrainCode.\n\n"
                f"Utilisateur : {message.strip()}\nChatbot :"
            )

        elif intent == "besoin_aide_financement":
            prompt = (
                "L’utilisateur cherche du financement. Explique si BrainCode propose un accompagnement ou des investisseurs.\n\n"
                f"Utilisateur : {message.strip()}\nChatbot :"
            )

        elif intent == "validation_etape":
            prompt = (
                "Explique comment une étape est validée dans BrainCode (IA, livrable, mentor). Réponds sans intro.\n\n"
                f"Utilisateur : {message.strip()}\nChatbot :"
            )

        elif intent == "trouver_coach":
            prompt = (
                "L’utilisateur veut savoir où trouver les infos sur son coach. Explique simplement où regarder dans la plateforme BrainCode.\n\n"
                f"Utilisateur : {message.strip()}\nChatbot :"
            )

        # 🔁 Sinon fallback sur prompt générique
        else:
            logging.info("🟡 Aucune intention détectée ou inconnue → prompt générique.")
            prompt = build_prompt(message, lang, history)

        # === Envoi à Ollama ===
        payload = {
            "model": "mistral",
            "prompt": prompt,
            "stream": True
        }

        async def stream_response():
            try:
                async with httpx.AsyncClient(timeout=120) as client:
                    async with client.stream("POST", OLLAMA_URL, json=payload) as response:
                        async for chunk in response.aiter_text():
                            chunk = chunk.strip()
                            if chunk:
                                for line in chunk.split("\n"):
                                    try:
                                        parsed = json.loads(line)
                                        text = parsed.get("response", "")
                                        if text:
                                            yield json.dumps({"response": text}) + "\n"
                                    except json.JSONDecodeError:
                                        logging.warning(f"⛔ Chunk ignoré : {line}")
            except Exception as e:
                logging.error(f"❌ Stream erreur fallback : {e}")
                yield json.dumps({"response": "Je suis désolé, je ne peux pas répondre pour le moment.", "error": str(e)}) + "\n"

        return StreamingResponse(stream_response(), media_type="text/plain")

    except Exception as e:
        logging.error(f"🔥 Erreur globale dans /chat-stream : {e}")
        return JSONResponse(status_code=500, content={
            "error": "Erreur serveur",
            "message": "Le chatbot a rencontré un problème. Réessaie dans un moment."
        })


# === PING BOT ===
@app.get("/ping")
async def ping():
    try:
        async with httpx.AsyncClient(timeout=2) as client:
            r = await client.get(OLLAMA_BASE)
            return {"status": "ok", "ollama": "online" if r.status_code == 200 else "offline"}
    except:
        return {"status": "ok", "ollama": "offline"}

# === DEBUG PROMPT (dev uniquement) ===
@app.get("/debug-prompt")
def debug_prompt():
    prompt = build_prompt("Comment rejoindre une étape ?", "fr", [])
    return {"prompt": prompt}

# === TEST SYNCHRONE (sans stream) ===
@app.get("/test-ollama")
async def test_ollama():
    try:
        payload = {
            "model": "mistral",
            "prompt": "Bonjour",
            "stream": False
        }
        async with httpx.AsyncClient(timeout=30) as client:
            response = await client.post(OLLAMA_URL, json=payload)
            return {
                "status_code": response.status_code,
                "response": response.json()
            }
    except Exception as e:
        return {
            "error": str(e),
            "trace": traceback.format_exc()
        }
