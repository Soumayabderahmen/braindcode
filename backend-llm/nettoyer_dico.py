def nettoyer_dictionnaire_brut(input_path="data/dico_brut.txt", output_path="data/french_dictionary.txt"):
    lignes_valides = []
    with open(input_path, "r", encoding="utf-8") as f_in:
        for ligne in f_in:
            mot = ligne.strip().split()[0]  # Ne garde que le premier mot
            if mot.isalpha():  # mot valide uniquement alphabétique
                lignes_valides.append(f"{mot.lower()} 1")

    with open(output_path, "w", encoding="utf-8") as f_out:
        f_out.write("\n".join(lignes_valides))

    print(f"✅ Dictionnaire nettoyé : {len(lignes_valides)} mots valides")
