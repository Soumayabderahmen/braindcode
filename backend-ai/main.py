from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from nlu_rasa import get_rasa_response
from transformers_fallback import get_transformers_response
from fastapi.middleware.cors import CORSMiddleware

from langdetect import detect

def detect_language(text):
    try:
        return detect(text)
    except:
        return "unknown"

app = FastAPI()

@app.post("/chat")
async def chat(request: Request):
    data = await request.json()
    message = data.get("message", "")
    sender_id = data.get("sender", "guest")

    print(f"💬 Reçu message de {sender_id} : {message}")

    # 🌐 Détection de la langue
    lang = detect_language(message)
    print(f"🌐 Langue détectée : {lang}")

    # 🔍 D'abord : tenter avec Rasa
    rasa_response = get_rasa_response(sender_id, message)
    print("🟡 Réponse Rasa:", rasa_response)

    if rasa_response:
        return JSONResponse({
            "reply": rasa_response,
            "source": "rasa",
            "language": lang
    })

# Si Rasa ne répond pas
    print("❌ Aucune réponse de Rasa, fallback vers Transformers")

    # 🧠 Sinon : fallback vers Transformers
    fallback = get_transformers_response(message)
    print("🔵 Réponse Transformers:", fallback)

    return JSONResponse({
        "reply": fallback,
        "source": "transformers",
        "language": lang
    })

@app.get("/ping")
async def ping():
    return {"status": "ok"}

# Autoriser les requêtes venant de localhost:8000 (Laravel/Vite)
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://127.0.0.1:8000"],  # ou "*" pour tout autoriser (dev uniquement)
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
