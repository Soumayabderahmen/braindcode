# ✅ Base image stable
FROM python:3.9-slim

# ✅ Crée un répertoire de travail
WORKDIR /app

# ✅ Copier uniquement requirements.txt d'abord pour cache
COPY requirements.txt .

# ✅ Installer les dépendances (plus rapide en cache)
RUN pip install --upgrade pip && pip install -r requirements.txt

# ✅ Ensuite copier tout le projet
COPY . .

# ✅ Expose le port de l'API
EXPOSE 5005

# ✅ Démarre le serveur FastAPI avec Uvicorn
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "5005", "--reload"]
