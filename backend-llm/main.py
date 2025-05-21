from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from langdetect import detect
import httpx
import os
import logging
import traceback

# === CONFIGURATION ===
OLLAMA_BASE = os.getenv("OLLAMA_API", "http://127.0.0.1:11434") 
OLLAMA_URL = f"{OLLAMA_BASE}/api/generate"  # ✅ Fixé ici

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

# === PROMPT BUILDER ===
def build_prompt(message: str, lang: str, history: list = None) -> str:
    instruction = {
        "fr": (
            "Tu es le chatbot intelligent du BrainCode Startup Studio. "
            "Ta mission est d’aider les startups à progresser dans leur parcours d’incubation en 12 étapes. "
            "Comprends leur situation, pose des questions si nécessaire, et propose des réponses claires, utiles et professionnelles."
        ),
        "en": (
            "You are the intelligent chatbot of BrainCode Startup Studio. "
            "Your mission is to guide startups through the 12-step incubation journey. "
            "Understand their context, ask clarifying questions if needed, and provide clear, helpful, and professional answers."
        )
    }.get(lang, "You are BrainBot, BrainCode's helpful assistant. Keep responses short and kind.")

    prompt = f"{instruction}\n\n"
    if history:
        for h in history[-2:]:
            role = "Utilisateur" if h["sender"] == "user" else "Chatbot"
            prompt += f"{role} : {h['text'].strip()}\n"
    prompt += f"Utilisateur : {message.strip()}\nChatbot :"
    return prompt

# === ENDPOINT PRINCIPAL ===
@app.post("/chat")
async def chat(request: Request):
    try:
        data = await request.json()
        message = data.get("message", "").strip()
        history = data.get("history", [])

        if not message:
            return JSONResponse(status_code=400, content={"error": "Message vide"})

        lang = detect(message)
        if lang not in ["fr", "en"]:
            logging.warning(f"Langue inconnue détectée ({lang}), fallback en FR")
            lang = "fr"

        prompt = build_prompt(message, lang, history)

        # ✅ Nouveau payload compatible avec /api/generate
        payload = {
            "model": "mistral",   # ou llama3, llama2, selon ce que tu as
            "prompt": prompt,
            "stream": False
        }

        async with httpx.AsyncClient(timeout=60.0) as client:
            response = await client.post(OLLAMA_URL, json=payload)
            response.raise_for_status()
            result = response.json()

            answer = result.get("response", "").strip()  # ✅ clé correcte ici

            return {
                "response": answer,
                "lang": lang
            }

    except Exception as e:
        logging.error(f"Erreur dans /chat : {e}")
        logging.error(traceback.format_exc())
        return JSONResponse(status_code=500, content={
            "error": "Erreur interne du serveur",
            "details": str(e)
        })


@app.get("/ping")
async def ping():
    try:
        async with httpx.AsyncClient(timeout=2) as client:
            r = await client.get(OLLAMA_BASE)
            return {"status": "ok", "ollama": "online" if r.status_code == 200 else "offline"}
    except:
        return {"status": "ok", "ollama": "offline"}


@app.get("/debug-prompt")
def debug_prompt():
    prompt = build_prompt("Comment rejoindre une étape ?", "fr", [])
    return {"prompt": prompt}


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
