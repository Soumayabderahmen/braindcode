from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
import requests
from langdetect import detect

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

def detect_language(text):
    try:
        return detect(text)
    except:
        return "unknown"

@app.post("/chat")
async def chat(request: Request):
    data = await request.json()
    message = data.get("message", "")
    lang = detect_language(message)

    try:
        response = requests.post(
            "http://127.0.0.1:11434/api/generate",
            json={
                "model": "llama3",
                "prompt": message,
                "stream": False
            },
            timeout=300  # ⏱️ Augmente le timeout 5 minutes
        )
        response.raise_for_status()
        json_data = response.json()
        bot_response = json_data.get("response", "").strip()

        if not bot_response:
            bot_response = "Désolé, je n'ai pas bien compris."

        return JSONResponse({
            "reply": bot_response,
            "language": lang,
            "source": "ollama"
        })

    except Exception as e:
        print("Erreur backend LLM:", str(e))
        return JSONResponse({
            "reply": f"⏱️ Temps dépassé ou erreur LLM : {str(e)}",
            "language": lang,
            "source": "offline"
    })


@app.get("/ping")
def ping():
    return {"status": "ok"}

@app.get("/test-ollama")
def test_ollama():
    try:
        r = requests.get("http://localhost:11434")
        return {"status": "reachable", "content": r.text}
    except Exception as e:
        return {"status": "error", "error": str(e)}
