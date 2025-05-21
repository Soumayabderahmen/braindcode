# ==== core/corrector.py ====
from symspellpy import SymSpell, Verbosity
from fuzzywuzzy import process
from langdetect import detect, DetectorFactory, LangDetectException

# 🔒 Rendre langdetect plus stable
DetectorFactory.seed = 42

# Chargement du correcteur
sym_spell = SymSpell(max_dictionary_edit_distance=2, prefix_length=7)
sym_spell.load_dictionary("data/french_dictionary.txt", term_index=0, count_index=1)
with open("data/french_dictionary.txt", encoding="utf-8") as f:
    DICTIONARY_WORDS = [line.split()[0] for line in f.readlines()]

def corriger_fautes(message: str) -> str:
    mots = message.strip().split()
    message_symspell = []
    for mot in mots:
        suggestions = sym_spell.lookup(mot, Verbosity.CLOSEST, max_edit_distance=2)
        message_symspell.append(suggestions[0].term if suggestions else mot)
    phrase_blob = " ".join(message_symspell)
    mots_finals = []
    for mot in phrase_blob.split():
        best, score = process.extractOne(mot, DICTIONARY_WORDS)
        mots_finals.append(best if score > 90 else mot)
    return " ".join(mots_finals)

def detect_language(text: str) -> str:
    try:
        detected = detect(text)
        return "fr" if detected == "fr" else "en"
    except LangDetectException:
        return "fr"
