def convertir_fichier():
    with open("data/dico_brut.txt", "r", encoding="utf-8") as f_in:
        lignes = f_in.readlines()

    with open("data/french_dictionary.txt", "w", encoding="utf-8") as f_out:
        for ligne in lignes:
            mot = ligne.strip()
            if mot:
                f_out.write(f"{mot} 1\n")  # on donne 1 comme fréquence

# Exécution
convertir_fichier()
