from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from langdetect import detect
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

def detect_intention(message: str):
    message = message.lower().strip()

    if "étape" in message and re.search(r"étape\s+(\d+)", message):
        match = re.search(r"étape\s+(\d+)", message)
        etape = match.group(1)
        for intent in INTENTIONS:
            if intent["name"] == "probleme_etape":
                prompt = intent["prompt_template"].replace("{{etape}}", etape)
                return intent["name"], prompt

    for intent in INTENTIONS:
        if intent["name"] == "liste_etapes_programme" and any(kw in message for kw in [
            # 🇫🇷 Français
            "étapes", "etapes", "programme d'incubation", "incubation", "étapes du programme",
            "liste des étapes", "plan d'accompagnement", "les étapes", "étape 1", "étapes startup",
            "parcours startup", "12 étapes", "structure du programme", "phases d'incubation",
            "donner les étapes", "comment fonctionne le programme", "etapes d'accompagnement",
    
            # 🇬🇧 Anglais
            "12 steps", "steps of incubation", "startup steps", "what are the 12 steps",
            "give me the 12 steps", "steps of the program", "incubation steps", "program steps"
]):
            return intent["name"], intent["prompt_template"]

    for intent in INTENTIONS:
        if intent["name"] == "salutation" and any(word in message for word in ["salut", "bonjour", "hello", "hey", "hi", "bonsoir"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "merci" and any(word in message for word in ["merci", "je te remercie"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "demande_mentor" and any(word in message for word in ["mentor", "accompagnement", "coach"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "probleme_connection" and any(word in message for word in ["connexion", "connecter", "mot de passe"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "question_faq" and any(word in message for word in ["c’est quoi", "qu’est-ce que", "ça veut dire"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "demande_aide_document" and any(word in message for word in ["trouver", "où est", "où puis-je", "document", "business model", "canvas"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "question_sur_livrable" and any(word in message for word in ["livrable", "remettre", "document à envoyer", "quoi faire", "ce qu’on attend"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "probleme_delai" and any(word in message for word in ["retard", "pas eu le temps", "délai", "prolonger", "finir plus tard"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "demande_contact_admin" and any(word in message for word in ["admin", "administrateur", "contacter", "équipe", "contact"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "idee_marketing" and any(word in message for word in ["stratégie marketing", "plan marketing", "idée marketing"]):
            return intent["name"], intent["prompt_template"]

        if intent["name"] == "idee_generale" and any(word in message for word in ["idée", "donner des idées", "des idées", "proposer une idée"]):
            return intent["name"], intent["prompt_template"]

    return None, f"L'utilisateur a dit : {message}"

@app.post("/chat")
async def chat(request: Request):
    data = await request.json()
    message = data.get("message", "").strip()
    message = corriger_fautes(message)
    lang = detect_language(message)
    intent_name, prompt_from_intent = detect_intention(message)

    message = re.sub(r'\s+', ' ', message).strip()[:500]
    logging.info(f"[Message utilisateur] {message}")

    if intent_name and prompt_from_intent:
        prompt_to_send = prompt_from_intent
    else:
        prompt_to_send = generate_language_sensitive_prompt(message)

    if intent_name == "liste_etapes_programme":
        logging.info(f"[Réponse statique envoyée directement sans Ollama]")
        await asyncio.sleep(3)

        if lang == "en":
            return JSONResponse({
                "reply": "Here are the 12 startup incubation steps:\n\n1. Identify a problem\n2. Design an innovative solution\n3. Test the hypothesis\n4. Build the founding team\n5. Create a minimum viable product (MVP)\n6. Collect feedback and iterate\n7. Expand user base\n8. Manage financial resources\n9. Develop an effective marketing strategy\n10. Establish strategic partnerships\n11. Improve user experience\n12. Prepare to scale and grow",
                "language": lang,
                "source": "static",
                "intent": intent_name
        })

        return JSONResponse({
            "reply": prompt_to_send,
            "language": lang,
            "source": "static",
            "intent": intent_name
    })

    final_prompt = prompt_to_send + "\n\n(Réponds en 500 caractères maximum)"
    logging.info(f"[Prompt final envoyé à Ollama] {final_prompt}")

    MAX_RETRIES = 2
    for attempt in range(MAX_RETRIES):
        try:
            start = time.time()
            async with httpx.AsyncClient(timeout=60.0) as client:
                response = await client.post(
                    "http://127.0.0.1:11434/api/generate",
                    json={
                        "model": "llama3:8b",
                        "prompt": final_prompt,
                        "stream": False,
                        "options": {"num_predict": 512}
                    }
                )
            duration = time.time() - start
            logging.info(f"[Réponse Ollama ✅] en {duration:.2f}s")
            result = response.json()
            reply = result.get("response", "").strip()

            if reply:
                return JSONResponse({
                    "reply": reply,
                    "language": lang,
                    "source": "ollama",
                    "intent": intent_name
                })

        except Exception as e:
            logging.error(f"[Tentative {attempt + 1}] ❌ Erreur Ollama : {type(e).__name__} - {e}")
            if attempt == MAX_RETRIES - 1:
                return JSONResponse({
                    "reply": f"⏱️ Temps dépassé ou erreur interne : {str(e)}",
                    "language": lang,
                    "source": "offline"
                })

@app.get("/ping")
async def ping():
    return {"status": "ok"}