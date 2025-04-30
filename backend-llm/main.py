from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from langdetect import detect
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.schema.document import Document
from langchain_community.llms import Ollama
from langchain_community.vectorstores import Chroma
from langchain_community.document_loaders import TextLoader
from langchain_ollama import OllamaEmbeddings
from langchain_ollama import OllamaLLM as Ollama
from fastapi.responses import StreamingResponse

import os
import httpx
import logging
import time
import re
import json
import asyncio
app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

logging.basicConfig(level=logging.INFO)
# Initialisation de l'embedding + LLM
embedding_model = OllamaEmbeddings(model="nomic-embed-text")
llm = Ollama(model="llama3")




# Création du vecteurstore à partir du fichier intentions.txt
text_splitter = RecursiveCharacterTextSplitter(chunk_size=300, chunk_overlap=50)
intentions_path = "intentions/intentions.txt"

def create_intentions_db():
    loader = TextLoader(intentions_path)
    documents = loader.load()
    texts = text_splitter.split_documents(documents)
    return Chroma.from_documents(texts, embedding_model, persist_directory="./chroma-intentions")

intentions_db = create_intentions_db()
# Charger les intentions depuis le fichier JSON
with open("intentions.json", encoding="utf-8") as f:
    INTENTIONS = json.load(f)

def detect_language(text):
    try:
        return detect(text)
    except:
        return "unknown"

def corriger_fautes(message: str) -> str:
    corrections = {
        "programmaton": "programme",
        "incubatio": "incubation",
        "etapes": "étapes",
        "etape": "étape",
        "donner les etapes": "donner les étapes",
        "quelles sont les etapes": "quelles sont les étapes",
        "etapes de programmaton": "étapes de programme",
        "donner les étapes de programmaton": "donner les étapes du programme"
    }
    for faute, correct in corrections.items():
        message = message.replace(faute, correct)
    return message

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
        return f"You are a helpful assistant. Respond simply and naturally to the user message: {message}"

def detect_intention_semantic(message: str):
    try:
        results = intentions_db.similarity_search_with_score(message, k=1)
        if not results:
            logging.info("[Intentions] Aucun résultat trouvé.")
            return None, None
        
        best_match: tuple[Document, float] = results[0]
        doc, score = best_match

        logging.info(f"[Intentions] Similarité: {score:.4f}")
        if score > 0.3:
            return None, None  # Trop éloigné

        return "intent_detected", doc.page_content.strip()

    except Exception as e:
        logging.error(f"[Intentions ERROR] {e}")
        return None, None


@app.post("/chat")
async def chat(request: Request):
    data = await request.json()
    message = data.get("message", "").strip()
    message = corriger_fautes(message)
    lang = detect_language(message)
    intent_name, prompt_from_intent = detect_intention_semantic(message)

    message = re.sub(r'\s+', ' ', message).strip()[:500]
    message = re.sub(r"[^a-zA-ZÀ-ÿ0-9\s]", "", message).lower()
    logging.info(f"[Message utilisateur] {message}")

    if intent_name and prompt_from_intent:
        prompt_to_send = prompt_from_intent
    else:
        prompt_to_send = generate_language_sensitive_prompt(message)

    if intent_name == "liste_etapes_programme":
        logging.info(f"[Réponse statique envoyée directement sans Ollama]")
        await asyncio.sleep(1)  # Petit délai pour l'effet
        if lang == "en":
            return JSONResponse({
                "reply": "Here are the 12 startup incubation steps:\n\n1. Identify a problem\n2. Design an innovative solution\n3. Test the hypothesis\n4. Build the founding team\n5. Create a minimum viable product (MVP)\n6. Collect feedback and iterate\n7. Expand user base\n8. Manage financial resources\n9. Develop an effective marketing strategy\n10. Establish strategic partnerships\n11. Improve user experience\n12. Prepare to scale and grow",
                "language": lang,
                "source": "static",
                "intent": intent_name
            })
        else:
            return JSONResponse({
                "reply": prompt_to_send,
                "language": lang,
                "source": "static",
                "intent": intent_name
            })

    final_prompt = prompt_to_send + "\n\n(Réponds en 500 caractères maximum)"
    logging.info(f"[Prompt final envoyé à Ollama] {final_prompt}")

    async def stream_generator():
        MAX_RETRIES = 2
        for attempt in range(MAX_RETRIES):
            try:
                async with httpx.AsyncClient(timeout=60.0) as client:
                    response = await client.post(
                        "http://127.0.0.1:11434/api/generate",
                        json={
                            "model": "llama3:8b",
                            "prompt": final_prompt,
                            "stream": True,
                            "options": {"num_predict": 512}
                        }
                    )
                    async for line in response.aiter_lines():
                        if line.strip():
                            try:
                                data = json.loads(line)
                                part = data.get("response", "")
                                if part:
                                    yield part.encode("utf-8")
                                    await asyncio.sleep(0.01)  # Petit délai pour lisser le stream
                            except Exception as e:
                                logging.error(f"[Stream Parsing Error] {e}")
                break  # Pas d'erreur, sortir
            except Exception as e:
                logging.error(f"[Tentative {attempt + 1}] ❌ Erreur Ollama : {type(e).__name__} - {e}")

        yield "\n\n[FIN]".encode("utf-8")

    return StreamingResponse(stream_generator(), media_type="text/plain")

@app.post("/intentions/train")
async def retrain_intentions():
    try:
        global intentions_db
        intentions_db = create_intentions_db()
        logging.info("[Intentions] Base de vecteurs rechargée avec succès.")
        return {"status": "success", "message": "Base des intentions mise à jour."}
    except Exception as e:
        logging.error(f"[Intentions Reload ERROR] {e}")
        return JSONResponse(status_code=500, content={"error": str(e)})


@app.get("/ping")
async def ping():
    return {"status": "ok"}