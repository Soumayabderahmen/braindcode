from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from langdetect import detect
from fastapi.responses import StreamingResponse
import httpx
import logging
import time
import re
#main.py
app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

logging.basicConfig(level=logging.INFO)

def detect_language(text):
    try:
        return detect(text)
    except:
        return "unknown"

# ✅ Route PRINCIPALE pour le chatbot
@app.post("/chat")
async def chat(request: Request):
    data = await request.json()
    message = data.get("message", "").strip()
    lang = detect_language(message)

    # Limiter message + nettoyage
    message = re.sub(r'\s+', ' ', message).strip()[:500]
    logging.info(f"[Prompt reçu] {message}")

    MAX_RETRIES = 2
    for attempt in range(MAX_RETRIES):
        try:
            start = time.time()
            prompt = generate_language_sensitive_prompt(message) + "\n\n(Réponds en 500 caractères maximum)"

            async with httpx.AsyncClient(timeout=60.0) as client:
                response = await client.post(
                    "http://127.0.0.1:11434/api/generate",
                    json={
                        "model": "llama3",
                        "prompt": prompt,
                        "stream": False,
                        "options": {"num_predict": 512}
                    }
                )

            duration = time.time() - start
            logging.info(f"[Réponse Ollama] ✅ en {duration:.2f}s")

            result = response.json()
            reply = result.get("response", "").strip()

            if reply:
                return JSONResponse({
                    "reply": reply,
                    "language": lang,
                    "source": "ollama"
                })

        except Exception as e:
            logging.error(f"[Tentative {attempt + 1}] ❌ Erreur Ollama : {type(e).__name__} - {e}")
            if attempt == MAX_RETRIES - 1:
                return JSONResponse({
                    "reply": f"⏱️ Temps dépassé ou erreur interne : {str(e)}",
                    "language": lang,
                    "source": "offline"
                })

# ✅ Vérifier le statut
@app.get("/ping")
async def ping():
    return {"status": "ok"}

# ✅ Test manuel depuis navigateur
@app.get("/debug-ollama")
async def debug_ollama():
    prompt = "Test de connectivité avec le modèle LLaMA 3."

    try:
        start = time.time()
        async with httpx.AsyncClient(timeout=300.0) as client:
            response = await client.post(
                "http://localhost:11434/api/generate",
                json={"model": "llama3", "prompt": prompt, "stream": False}
            )
        duration = time.time() - start

        if response.status_code == 200:
            content = response.json().get("response", "").strip()
            return {"status": "ok", "response": content, "time": f"{duration:.2f}s"}
        else:
            return {"status": "error", "code": response.status_code}

    except Exception as e:
        return {"status": "failed", "error": str(e)}
    
def generate_language_sensitive_prompt(message: str) -> str:
    try:
        lang = detect(message)
    except Exception:
        lang = "unknown"

    if lang == "fr":
        return (
            "Tu es un assistant IA sympathique. Si quelqu’un écrit 'bonjour' ou 'salut', "
            "réponds simplement par une salutation naturelle comme 'Bonjour ! 😊', 'Salut !', ou autre. "
            f"Voici le message utilisateur : {message}"
        )
    elif lang == "en":
        return (
            "You are a friendly AI assistant. If the user says 'hello' or 'hi', "
            "just respond with a natural greeting like 'Hey there!', 'Hello! 😊', or something similar. "
            f"Here is the user message: {message}"
        )
    else:
        return (
            f"You are a helpful assistant. Respond simply and naturally to the user message: {message}"
        )