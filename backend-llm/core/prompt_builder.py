# ==== core/prompt_builder.py ====
def build_personalized_prompt(intent_name: str, user_message: str, lang: str, context: str) -> str:
    def response(fr: str, en: str) -> str:
        return fr if lang == "fr" else en

    base = (
    "Tu es BrainBot, un assistant IA du programme BrainCode. "
    "Réponds simplement et directement. Pas plus de 2 phrases."
    if lang == "fr" else
    "You are BrainBot, an AI assistant for the BrainCode program. "
    "Reply simply and directly. No more than 2 sentences."
)
    if context:
        base += f"\n\n{context}"
    base += f"\n\nMessage utilisateur : {user_message}" if lang == "fr" else f"\n\nUser message: {user_message}"

    prompts = {
        "salutation": response(
            "\n\nBonjour 👋 Comment puis-je t’aider aujourd’hui ?",
            "\n\nHello 👋 How can I help you today?"
        ),
        "liste_etapes_programme": response(
            "\n\n12 étapes de l'incubation d'une startup :\n"
            "1. Idée & validation\n"
            "2. Mise en place du comité de pilotage\n"
            "3. Étude de marché & analyse concurrentielle\n"
            "4. Définition du business model\n"
            "5. Création du plan stratégique\n"
            "6. Élaboration du pitch\n"
            "7. Résolution des problèmes techniques\n"
            "8. Mise en place des processus opérationnels\n"
            "9. Recrutement du premier équipe\n"
            "10. Lancement du produit MVP (Minimum Viable Product)\n"
            "11. Collecte de feedback & ajustement\n"
            "12. Scaling et mise en marché\n"
            "Bon vent pour ton startup ! 😊",

            "\n\n12 stages of startup incubation:\n"
            "1. Idea & validation\n"
            "2. Steering committee setup\n"
            "3. Market study & competitive analysis\n"
            "4. Business model definition\n"
            "5. Strategic plan creation\n"
            "6. Pitch preparation\n"
            "7. Technical problem solving\n"
            "8. Operational processes setup\n"
            "9. First team recruitment\n"
            "10. MVP (Minimum Viable Product) launch\n"
            "11. Feedback collection & adjustments\n"
            "12. Scaling and go-to-market\n"
            "Good luck with your startup! 😊"
        ),

        "objectif_du_programme": response(
            "\n\nBrainCode t’aide à transformer ton idée en startup 🚀 étape par étape.",
            "\n\nBrainCode helps you turn your idea into a startup 🚀 step by step."
        ),
        "contact_mentor": response(
            "\n\nTu peux contacter ton coach ici 👉 https://braincode.tn/dashboard/coach",
            "\n\nYou can contact your coach here 👉 https://braincode.tn/dashboard/coach"
        ),
        "livrable": response(
            "\n\nChaque étape contient un livrable à remplir. Va dans ton espace personnel pour y accéder 📁",
            "\n\nEach step includes a deliverable. Go to your dashboard to complete it 📁"
        ),
        "retard": response(
            "\n\nPas de stress ! Tu peux demander un délai à ton coach dans ton espace personnel. ⏳",
            "\n\nNo stress! You can ask your coach for more time in your dashboard. ⏳"
        ),
        "prise_de_rdv": response(
            "\n\nTu peux prendre rendez-vous avec ton coach depuis la section 'Coach' 📅",
            "\n\nYou can book a meeting with your coach from the 'Coach' section 📅"
        ),
        "besoin_aide_financement": response(
            "\n\nTu peux explorer les options de financement dans l’onglet Investisseurs 💰",
            "\n\nYou can explore funding options in the Investors section 💰"
        ),
        "validation_etape": response(
            "\n\nUne fois ton livrable soumis, ton coach ou l’IA le valide. Tu seras notifié. ✅",
            "\n\nOnce you submit your deliverable, your coach or the AI will validate it. You'll get notified. ✅"
        ),
        "trouver_coach": response(
            "\n\nTu peux consulter le profil de ton coach dans ton tableau de bord section Coach 👤",
            "\n\nYou can find your coach’s profile in your dashboard under the Coach section 👤"
        ),
    }

    # ✅ Fallback multilingue intelligent
    default_prompt = response(
    "\n\nRéponds de façon claire, pédagogique et structurée.",
    "\n\nReply in a clear, helpful and structured way."
)

    return (base + prompts.get(intent_name, default_prompt)).strip()
