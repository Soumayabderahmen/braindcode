# ✅ Nouveau fichier core/prompt_engine.py
from core.prompt_builder import build_personalized_prompt

def generate_prompt(
    message: str,
    lang: str,
    history: list = [],
    intent_override: str = None,
    user_context: str = None,
    user_type: str = None
) -> str:
    # Historique
    short_history = [
        f"{'User' if m['sender'] == 'user' else 'Bot'}: {m['text'].strip()}"
        for m in history if 'sender' in m and 'text' in m
    ][-3:]
    history_context = "\n".join(short_history)
    if len(history_context) > 300:
        history_context = history_context[-300:]

    # System prompt
    system = (
        "Tu es BrainBot, assistant IA du programme BrainCode. Réponds avec bienveillance et brièveté."
        if lang == "fr" else
        "You are BrainBot, an AI assistant for BrainCode. Be helpful and concise."
    )

    if user_type:
        if user_type == "startup":
            system += "\nTu parles à une startup."
        elif user_type == "coach":
            system += "\nTu parles à un coach."
        elif user_type == "investisseur":
            system += "\nTu parles à un investisseur."

    if user_context:
        system += f"\nContexte utilisateur : {user_context}"

    final_prompt = f"{system}\n\nMessage : {message}"
    if history_context:
        final_prompt += f"\n\nHistorique :\n{history_context}"

    return final_prompt, "fallback"
