@echo off
echo 🔥 Lancement du projet Braindcode...

start "Frontend (npm)" cmd /k "npm run dev"
start "Laravel" cmd /k "php artisan serve"
start "FastAPI" cmd /k "cd backend-ai && call venv\\Scripts\\activate && uvicorn main:app --host 127.0.0.1 --port 5005 --reload"
start "Rasa Server" cmd /k "cd backend-ai && call venv\\Scripts\\activate && rasa run --enable-api --port 5006"
start "Rasa Actions" cmd /k "cd backend-ai && call venv\\Scripts\\activate && rasa run actions"

echo ✅ Tous les services sont lancés dans des terminaux séparés.
pause
