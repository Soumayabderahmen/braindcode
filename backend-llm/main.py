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
import asyncio

# === CONFIGURATION ===
OLLAMA_MODEL = os.getenv("LLM_MODEL", "llama3:8b")
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

# === PROMPT BUILDER OPTIMISÉ ===
def build_prompt_optimized(message: str, lang: str, history: list = None, intent: str = None) -> str:
    lower_msg = message.lower()

    # 🔍 Salutations simples → réponse brève et directe
    salutations = ["bonjour", "salut", "coucou", "hello", "bonsoir", "hey"]
    if lower_msg in salutations:
        return {
            "fr": (
                "Tu es BrainBot. Réponds en UNE SEULE PHRASE courte et accueillante.\n\n"
                f"Utilisateur : {message}\nChatbot :"
            ),
            "en": (
                "You are BrainBot. Respond in ONE SHORT welcoming sentence.\n\n"
                f"User: {message}\nChatbot:"
            )
        }.get(lang, f"Utilisateur : {message}\nChatbot :")

    # 🎯 Instructions optimisées pour réponses courtes
    instruction = {
        "fr": (
            "Tu es BrainBot, assistant BrainCode. RÉPONDS EN MAXIMUM 2 PHRASES COURTES ET PRÉCISES. "
            "Sois direct, utile et concis sur les sujets BrainCode uniquement : programme, étapes, livrables, coachs, mentors, dashboard."
        ),
        "en": (
            "You are BrainBot, BrainCode assistant. RESPOND IN MAXIMUM 2 SHORT AND PRECISE SENTENCES. "
            "Be direct, helpful and concise about BrainCode topics only: program, steps, deliverables, coaches, mentors, dashboard."
        )
    }.get(lang, "Tu es BrainBot. Sois très bref et précis.")

    prompt = f"{instruction}\n\n"

    # Limiter l'historique pour réduire la taille du prompt
    if history:
        for h in history[-1:]:  # Seulement le dernier échange au lieu de 2
            role = "Utilisateur" if h["sender"] == "user" else "Chatbot"
            prompt += f"{role} : {h['text'].strip()[:100]}\n"  # Tronquer à 100 chars

    prompt += f"Utilisateur : {message.strip()}\nChatbot :"
    return prompt

# === STREAMING OPTIMISÉ AVEC DÉLAIS RÉDUITS ===
async def stream_response_optimized(payload):
    """Version optimisée du streaming avec délais réduits"""
    try:
        start = asyncio.get_event_loop().time()
        async with httpx.AsyncClient(timeout=120) as client:
            async with client.stream("POST", OLLAMA_URL, json=payload) as response:
                buffer = ""
                first_chunk_sent = False
                word_count = 0

                async for chunk in response.aiter_text():
                    chunk = chunk.strip()
                    if not chunk:
                        continue

                    for line in chunk.split("\n"):
                        try:
                            parsed = json.loads(line)
                            text = parsed.get("response", "")
                            if not text:
                                continue

                            buffer += text

                            # ✅ Premier chunk immédiat (pas de délai)
                            if not first_chunk_sent and buffer:
                                yield json.dumps({"response": buffer}) + "\n"
                                buffer = ""
                                first_chunk_sent = True
                                continue  # Pas de sleep pour le premier chunk

                            # ✅ Streaming mot par mot avec délais réduits
                            while " " in buffer:
                                word, buffer = buffer.split(" ", 1)
                                word_count += 1
                                yield json.dumps({"response": word + " "}) + "\n"
                                
                                # Délai adaptatif : plus rapide au début, plus lent pour les mots longs
                                if word_count <= 5:
                                    await asyncio.sleep(0.005)  # 5ms pour les premiers mots
                                else:
                                    delay = min(0.025, max(0.005, len(word) * 0.002))  
                                    await asyncio.sleep(delay)

                        except json.JSONDecodeError:
                            logging.warning(f"⛔ Chunk JSON mal formé ignoré : {line}")

        # Envoyer le buffer restant
        if buffer.strip():
            yield json.dumps({"response": buffer.strip() + " "}) + "\n"
            
        duration = asyncio.get_event_loop().time() - start
        logging.info(f"⏱️ Streaming terminé en {duration:.2f} secondes")

    except Exception as e:
        logging.error(f"❌ Erreur dans le streaming Ollama : {e}")
        yield json.dumps({
            "response": "Je suis désolé, je ne peux pas répondre pour le moment.",
            "error": str(e)
        }) + "\n"

# === ALTERNATIVE : STREAMING PAR CHUNKS PLUS GROS ===
async def stream_response_chunked(payload):
    """Alternative avec streaming par chunks de caractères"""
    try:
        start = asyncio.get_event_loop().time()
        async with httpx.AsyncClient(timeout=120) as client:
            async with client.stream("POST", OLLAMA_URL, json=payload) as response:
                accumulated_text = ""
                char_count = 0
                
                async for chunk in response.aiter_text():
                    chunk = chunk.strip()
                    if not chunk:
                        continue

                    for line in chunk.split("\n"):
                        try:
                            parsed = json.loads(line)
                            text = parsed.get("response", "")
                            if text:
                                accumulated_text += text
                                char_count += len(text)
                                
                                # Envoyer par chunks de 3-5 caractères
                                if char_count >= 4:
                                    yield json.dumps({"response": text}) + "\n"
                                    char_count = 0
                                    await asyncio.sleep(0.008)  # 8ms entre les chunks

                        except json.JSONDecodeError:
                            continue

        duration = asyncio.get_event_loop().time() - start
        logging.info(f"⏱️ Streaming chunked terminé en {duration:.2f} secondes")

    except Exception as e:
        logging.error(f"❌ Erreur streaming chunked : {e}")
        yield json.dumps({"response": "Erreur de streaming"}) + "\n"

@app.on_event("startup")
async def warmup_ollama():
    """Warmup optimisé avec prompt court"""
    logging.info("🚀 Démarrage du backend... Warmup Ollama lancé.")
    try:
        payload = {
            "model": OLLAMA_MODEL,
            "prompt": "Salut",  # Prompt encore plus court
            "stream": False,
            "options": {
                "max_tokens": 50,  # Limiter la réponse de warmup
                "temperature": 0.7
            }
        }
        async with httpx.AsyncClient(timeout=30) as client:  # Timeout réduit
            response = await client.post(OLLAMA_URL, json=payload)
            if response.status_code == 200:
                logging.info("✅ Ollama prêt (warmup réussi)")
            else:
                logging.warning(f"⚠️ Warmup échoué - status code : {response.status_code}")
    except Exception as e:
        logging.error(f"❌ Warmup Ollama échoué : {e}")

# === ENDPOINT PRINCIPAL OPTIMISÉ ===
@app.post("/chat-stream")
async def chat_stream(request: Request):
    try:
        data = await request.json()
        message = data.get("message", "").strip()
        history = data.get("history", [])
        lang = detect(message)
        intent = data.get("intent_override") or detect_intent(message)
        
        # Choix du mode de streaming
        streaming_mode = data.get("streaming_mode", "optimized")  # "optimized" ou "chunked"

        logging.info(f"Intent détectée : {intent} | Mode: {streaming_mode}")

        # === PROMPTS SPÉCIALISÉS OPTIMISÉS ===
        if intent == "liste_etapes_programme":
            prompt = (
                "Tu es BrainBot. Liste brièvement les 12 étapes en une phrase par étape.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : Les 12 étapes BrainCode : 1.Idée/validation 2.Comité 3.Marché 4.Business model "
                "5.Plan stratégique 6.Pitch deck 7.Prototype 8.Processus 9.Recrutement 10.MVP 11.Feedback 12.Scaling.\n"
                "Chatbot :"
            )

        elif intent == "objectif_du_programme":
            prompt = (
                "Tu es BrainBot. Réponds en 1 phrase claire.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : BrainCode structure votre startup avec 12 étapes guidées, outils IA et accompagnement coach.\n"
                "Chatbot :"
            )

        elif intent == "prise_de_rdv":
            prompt = (
                "Tu es BrainBot. Une instruction directe.\n\n"
                f"Utilisateur : {message}\n"
                "Chatbot : Tableau de bord > Coach > 'Prendre rendez-vous'.\n"
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
            # Utiliser le prompt builder optimisé
            prompt = build_prompt_optimized(message, lang, history, intent)

        # === PAYLOAD OLLAMA OPTIMISÉ ===
        payload = {
            "model": OLLAMA_MODEL,
            "prompt": prompt,
            "stream": True,
            "keep_alive": 1,
            "options": {
                "temperature": 0.7,
                "top_p": 0.9,
                "repeat_penalty": 1.1
            }
        }

        # Choisir le mode de streaming
        if streaming_mode == "chunked":
            return StreamingResponse(
                stream_response_chunked(payload), 
                media_type="text/event-stream"
            )
        else:
            return StreamingResponse(
                stream_response_optimized(payload), 
                media_type="text/event-stream"
            )

    except Exception as e:
        logging.error(f"🔥 Erreur globale dans /chat-stream : {e}")
        return JSONResponse(status_code=500, content={
            "error": "Erreur serveur",
            "message": "Le chatbot a rencontré un problème. Réessaie dans un moment."
        })

# === ENDPOINTS UTILITAIRES ===
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
    prompt = build_prompt_optimized("Comment rejoindre une étape ?", "fr", [])
    return {"prompt": prompt}

@app.get("/test-ollama")
async def test_ollama():
    try:
        payload = {
            "model": OLLAMA_MODEL,
            "prompt": "Test rapide",
            "stream": False,
            "options": {"max_tokens": 30}
        }
        async with httpx.AsyncClient(timeout=20) as client:
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

# === NOUVEAU : ENDPOINT POUR TESTER LES MODES DE STREAMING ===
@app.post("/test-streaming")
async def test_streaming(request: Request):
    """Endpoint pour tester différents modes de streaming"""
    data = await request.json()
    mode = data.get("mode", "optimized")  # "optimized" ou "chunked"
    
    payload = {
        "model": OLLAMA_MODEL,
        "prompt": "Tu es BrainBot. Réponds en 2 phrases courtes sur BrainCode.\n\nUtilisateur : Bonjour\nChatbot :",
        "stream": True,
        "options": {"max_tokens": 50}
    }
    
    if mode == "chunked":
        return StreamingResponse(
            stream_response_chunked(payload), 
            media_type="text/event-stream"
        )
    else:
        return StreamingResponse(
            stream_response_optimized(payload), 
            media_type="text/event-stream"
        )