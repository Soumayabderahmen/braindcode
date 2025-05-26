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
print(f"OLLAMA_URL = {OLLAMA_URL}")

# === PROMPT BUILDER PAR DÉFAUT ===
def build_prompt(message: str, lang: str, history: list = None, intent: str = None) -> str:
    lower_msg = message.lower()

    # 🔍 Salutations simples → réponse brève
    salutations = ["bonjour", "salut", "coucou", "hello", "bonsoir", "hey"]
    if lower_msg in salutations:
        return {
            "fr": (
                "Tu es BrainBot, l’assistant de BrainCode. Si l’utilisateur dit juste bonjour, réponds brièvement et poliment.\n\n"
                f"Utilisateur : {message}\nChatbot : Bonjour 👋 Comment puis-je vous aider aujourd’hui ?"
            ),
            "en": (
                "You are BrainBot, the BrainCode assistant. If the user only says hello, respond briefly and politely.\n\n"
                f"User: {message}\nChatbot: Hello 👋 How can I assist you today?"
            )
        }.get(lang, f"Utilisateur : {message}\nChatbot : Bonjour 👋 Comment puis-je vous aider aujourd’hui ?")

    # 🎯 Sinon : instructions normales
    instruction = {
        "fr": (
            "Tu es BrainBot, l’assistant officiel de la plateforme BrainCode Startup Studio. "
            "Réponds en français, très brièvement (1 à 2 phrases max), de façon claire et directe, "
            "sur les sujets liés à BrainCode : programme, étapes, livrables, coachs, mentors, ou dashboard. "
            "Ne réponds que si la question est en rapport avec la plateforme."
        ),
        "en": (
            "You are BrainBot, the official assistant of the BrainCode Startup Studio platform. "
            "Reply in English, very briefly (1 to 2 sentences max), clearly and directly, only about BrainCode-related topics: "
            "program, steps, deliverables, coaches, mentors, or dashboard. Only answer if the question is relevant to the platform."
        )
    }.get(lang, "Tu es BrainBot. Sois bref, utile et concentre-toi uniquement sur les sujets liés à BrainCode.")

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
        intent = data.get("intent_override") or detect_intent(message)

        logging.info(f"Intent détectée : {intent}")

        if intent == "liste_etapes_programme":
            prompt = (
                "Tu es BrainBot. Réponds en français, sans introduction, en listant précisément les 12 étapes du programme BrainCode.\n\n"
                "Utilisateur : Quelles sont les étapes du programme ?\n"
                "Chatbot : Voici les 12 étapes de l'incubation d'une startup :\n"
                "1. Idée & validation\n2. Comité de pilotage\n3. Étude de marché\n4. Business model\n"
                "5. Plan stratégique\n6. Pitch deck\n7. Prototype\n8. Processus opérationnels\n"
                "9. Recrutement\n10. MVP\n11. Feedback\n12. Scaling\n\n"
                f"Utilisateur : {message}\nChatbot :"
            )

        elif intent == "objectif_du_programme":
            prompt = (
                "Tu es BrainBot. Réponds en 2 phrases claires.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : Le programme BrainCode aide les startups à structurer leur idée et tester leur marché. "
                "Il propose un parcours guidé, des outils concrets et un accompagnement IA + coachs.\n"
                "Chatbot :"
            )

        elif intent == "prise_de_rdv":
            prompt = (
                "Tu es BrainBot. Réponds en une phrase directe.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : Allez dans votre tableau de bord > section Coach > cliquez sur 'Prendre rendez-vous'.\n"
                "Chatbot :"
            )

        elif intent == "contact_mentor":
            prompt = (
                "Tu es BrainBot. Donne 3 étapes pour contacter un mentor.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : 1. Ouvrez votre tableau de bord. 2. Allez dans la section Mentors. 3. Cliquez sur un profil pour contacter.\n"
                "Chatbot :"
            )

        elif intent == "besoin_aide_financement":
            prompt = (
                "Tu es BrainBot. Réponds en 2 phrases simples.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : Consultez la section 'Financement' pour voir les aides disponibles. Vous pouvez aussi accéder aux investisseurs via l'espace ressources.\n"
                "Chatbot :"
            )

        elif intent == "validation_etape":
            prompt = (
                "Tu es BrainBot. Réponds brièvement.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : Une étape est validée si les livrables sont complétés et validés par l’IA ou un coach.\n"
                "Chatbot :"
            )

        elif intent == "retard":
            prompt = (
                "Tu es BrainBot. Rassure et propose des options.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : Pas de souci ! Demandez un délai, reprenez plus tard ou contactez un coach depuis le tableau de bord.\n"
                "Chatbot :"
            )

        elif intent == "trouver_coach":
            prompt = (
                "Tu es BrainBot. Sois direct.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : Accédez aux infos de votre coach depuis le tableau de bord > Mon Coach.\n"
                "Chatbot :"
            )

        elif intent == "livrable":
            prompt = (
                "Tu es BrainBot. Réponds exactement à la question sur les livrables sans reformulation.\n\n"
                f"Utilisateur : {message}\nChatbot :"
            )

        else:
            logging.info("🟡 Aucune intention détectée ou inconnue → génération du prompt dynamique.")
            prompt = build_prompt(message, lang, history, intent)

        # Envoi à Ollama
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
