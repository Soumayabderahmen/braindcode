import httpx

async def get_llm_response(message, lang, sender):
    prompt = f"{message}"
    try:
        async with httpx.AsyncClient() as client:
            response = await client.post(
                "http://localhost:11434/api/generate",
                json={"model": "llama3", "prompt": prompt, "stream": False}
            )
            result = response.json()
            return result.get("response", "Je n'ai pas compris.")
    except Exception as e:
        return "Erreur IA : je ne suis pas dispo pour le moment."
