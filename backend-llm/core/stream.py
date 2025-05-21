# ==== core/stream.py ====
import httpx, json, asyncio, logging, traceback
import os

from dotenv import load_dotenv  
load_dotenv()  

OLLAMA_API = os.getenv("OLLAMA_API", "http://127.0.0.1:11434")
MODEL_NAME = os.getenv("LLM_MODEL", "llama2:7b")

async def stream_generator(prompt: str):
    for attempt in range(2):  # ✅ Retry une fois
        try:
            timeout = httpx.Timeout(60.0, connect=10.0)
            async with httpx.AsyncClient(timeout=timeout) as client:
                resp = await client.post(
                    f"{OLLAMA_API}/api/generate",
                    json={
                        "model": MODEL_NAME,
                        "prompt": prompt,
                        "stream": False,
                        "options": {
                            "temperature": 0.5,
                            "stop": ["</s>"]
                        }
                    }
                )

                if resp.status_code != 200:
                    yield "⚠️ Le serveur IA est indisponible. Réessaye plus tard."
                    return

                data = resp.json()
                if "response" in data:
                    yield data["response"]
                else:
                    yield "⚠️ Aucune réponse générée."

        except httpx.TimeoutException:
            logging.error("[Timeout] Le backend IA a mis trop de temps à répondre.")
            yield "⚠️ Délai dépassé. Le bot a mis trop de temps à répondre."
            return

        except Exception as e:
            logging.error(f"[Stream Error] {type(e).__name__}: {e}")
            yield "⚠️ Une erreur inattendue est survenue."
            return

    yield "🚫 Échec IA. Réessaye plus tard."

