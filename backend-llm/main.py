from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse,StreamingResponse

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
        "Tu es BrainBot, l’assistant intelligent de BrainCode Startup Studio. "
        "Quand on te pose une question, réponds **seulement avec la réponse exacte**, sans introduction, ni explication, ni résumé. "
        "Par exemple, si on te demande une liste (comme les 12 étapes), réponds **juste avec la liste numérotée** proprement. "
        "N’ajoute pas de commentaire, de contexte, ou de message sympathique. Reste minimal et factuel."
    ),
    "en": (
        "You are BrainBot, the intelligent assistant of BrainCode Startup Studio. "
        "When asked a question, respond **only with the direct, exact answer**, without introduction, explanation, or summary. "
        "For example, if asked for a list (like the 12 steps), reply **with just the list**, clean and numbered. "
        "Do not add any comments, context, or friendly expressions. Keep it minimal and factual."
    )
}.get(lang, "You are BrainBot. Keep responses short and direct.")


    prompt = f"{instruction}\n\n"
    if history:
        for h in history[-2:]:
            role = "Utilisateur" if h["sender"] == "user" else "Chatbot"
            prompt += f"{role} : {h['text'].strip()}\n"
    prompt += f"Utilisateur : {message.strip()}\nChatbot :"
    return prompt

# === ENDPOINT PRINCIPAL ===
@app.post("/chat-stream")
async def chat_stream(request: Request):
    try:
        data = await request.json()
        message = data.get("message", "").strip()
        history = data.get("history", [])
        lang = detect(message)

        prompt = build_prompt(message, lang, history)

        payload = {
            "model": "mistral",
            "prompt": prompt,
            "stream": True
        }

        async def stream_response():
            async with httpx.AsyncClient(timeout=120) as client:
                async with client.stream("POST", OLLAMA_URL, json=payload) as response:
                    async for chunk in response.aiter_text():
                        yield chunk

        return StreamingResponse(stream_response(), media_type="text/plain")

    except Exception as e:
        logging.error(f"Erreur stream : {e}")
        return JSONResponse(status_code=500, content={"error": "Erreur interne", "details": str(e)})

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
