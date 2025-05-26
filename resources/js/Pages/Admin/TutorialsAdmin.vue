<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const tutorials = ref([])
const newTutorial = ref({
  title: '',
  type: 'url',
  video_url: '',
  file: null
})
const loading = ref(false)
const error = ref(null)
const success = ref(null)
const isFormExpanded = ref(false)

const handleFile = (e) => {
  newTutorial.value.file = e.target.files[0]
}
const editMode = ref(false)
const tutorialToEdit = ref(null)

const editTutorial = (tutorial) => {
  editMode.value = true
  tutorialToEdit.value = tutorial.id
  newTutorial.value = {
    title: tutorial.title,
    type: tutorial.type,
    video_url: tutorial.video_url,
    file: null
  }
  isFormExpanded.value = true
}

const addTutorial = async () => {
  if (!newTutorial.value.title.trim()) {
    error.value = "Le titre est requis"
    return
  }

  loading.value = true
  error.value = null

  try {
    let formData = new FormData()
    formData.append('title', newTutorial.value.title)
    formData.append('type', newTutorial.value.type)

    if (newTutorial.value.type === 'upload' && newTutorial.value.file) {
      formData.append('file', newTutorial.value.file)
    } else {
      formData.append('video_url', newTutorial.value.video_url)
    }

    if (editMode.value && tutorialToEdit.value) {
      await axios.post(`/admin/tutorials/${tutorialToEdit.value}?_method=PUT`, formData)
      success.value = "Tutoriel modifié avec succès !"
    } else {
      await axios.post('/admin/tutorials', formData)
      success.value = "Tutoriel ajouté avec succès !"
    }

    await fetchTutorials()
    resetForm()
  } catch (err) {
    error.value = "Erreur lors de l'enregistrement"
  } finally {
    loading.value = false
  }
}

const resetForm = () => {
  newTutorial.value = { title: '', type: 'url', video_url: '', file: null }
  editMode.value = false
  tutorialToEdit.value = null
  isFormExpanded.value = false
}

const fetchTutorials = async () => {
  loading.value = true
  try {
    const res = await axios.get('/admin/tutorials/list') 
    tutorials.value = res.data
  } catch (err) {
    error.value = "Erreur lors du chargement des tutoriels"
  } finally {
    loading.value = false
  }
}

const deleteTutorial = async (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce tutoriel ?')) {
    try {
      await axios.delete(`/admin/tutorials/${id}`)
      await fetchTutorials()
      success.value = "Tutoriel supprimé avec succès!"
      setTimeout(() => {
        success.value = null
      }, 3000)
    } catch (err) {
      error.value = "Erreur lors de la suppression"
    }
  }
}

const clearError = () => {
  error.value = null
}

const formatDate = (dateString) => {
  if (!dateString) return 'Date inconnue'
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(fetchTutorials)
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
      <!-- Header avec animation -->
      <div class="text-center mb-12">
        <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 via-sky-600 to-indigo-600 bg-clip-text text-transparent mb-4 animate-fade-in">
          Gestion des Tutoriels
        </h1>
        <p class="text-gray-600 text-lg animate-slide-up">
          Créez et gérez vos contenus pédagogiques en toute simplicité
        </p>
      </div>

      <!-- Messages de notification -->
      <div class="fixed top-4 right-4 z-50 space-y-2">
        <div v-if="success" class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in-right">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          {{ success }}
        </div>
        
        <div v-if="error" class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in-right">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
          </svg>
          {{ error }}
          <button @click="clearError" class="ml-2 hover:bg-red-600 rounded p-1">×</button>
        </div>
      </div>

      <!-- Formulaire d'ajout avec card moderne -->
      <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 mb-8 overflow-hidden">
        <div class="bg-gradient-to-r from-sky-300 to-sky-500 p-6">
          <button 
            @click="isFormExpanded = !isFormExpanded"
            class="w-full flex items-center justify-between text-white hover:text-blue-100 transition-colors"
          >
            <div class="flex items-center gap-3">
              <div class="bg-white/20 p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
              </div>
              <h2 class="text-2xl font-bold">
                {{ editMode ? 'Modifier le tutoriel' : 'Ajouter un nouveau tutoriel' }}
              </h2>
            </div>
            <svg 
              class="w-6 h-6 transform transition-transform duration-300"
              :class="{ 'rotate-180': isFormExpanded }"
              fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
        </div>

        <div 
          class="transition-all duration-500 ease-in-out overflow-hidden"
          :class="isFormExpanded ? 'max-h-[1000px] opacity-100' : 'max-h-0 opacity-0'"
        >
          <form @submit.prevent="addTutorial" class="p-6 space-y-6">
            <!-- Titre -->
            <div class="group">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Titre du tutoriel
              </label>
              <input 
                v-model="newTutorial.title" 
                type="text" 
                placeholder="Entrez le titre du tutoriel..."
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 outline-none bg-white/80"
                :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/20': error && !newTutorial.title.trim() }"
                required 
              />
            </div>

            <!-- Type de vidéo -->
            <div class="group">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Type de contenu
              </label>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label 
                  class="flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-300 hover:shadow-md"
                  :class="newTutorial.type === 'url' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white/80'"
                >
                  <input 
                    v-model="newTutorial.type" 
                    type="radio" 
                    value="url" 
                    class="hidden"
                  />
                  <div class="flex items-center gap-3">
                    <div 
                      class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                      :class="newTutorial.type === 'url' ? 'border-blue-500' : 'border-gray-300'"
                    >
                      <div 
                        v-if="newTutorial.type === 'url'" 
                        class="w-2 h-2 bg-blue-500 rounded-full"
                      ></div>
                    </div>
                    <span class="font-medium">Lien Internet</span>
                  </div>
                </label>
                
                <label 
                  class="flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-300 hover:shadow-md"
                  :class="newTutorial.type === 'upload' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white/80'"
                >
                  <input 
                    v-model="newTutorial.type" 
                    type="radio" 
                    value="upload" 
                    class="hidden"
                  />
                  <div class="flex items-center gap-3">
                    <div 
                      class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                      :class="newTutorial.type === 'upload' ? 'border-blue-500' : 'border-gray-300'"
                    >
                      <div 
                        v-if="newTutorial.type === 'upload'" 
                        class="w-2 h-2 bg-blue-500 rounded-full"
                      ></div>
                    </div>
                    <span class="font-medium">Fichier Local</span>
                  </div>
                </label>
              </div>
            </div>

            <!-- URL ou Fichier -->
            <!-- URL ou Fichier -->
            <div class="lg:col-span-2">
              <div v-if="newTutorial.type === 'url'">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  URL de la vidéo
                </label>
                <input 
                  v-model="newTutorial.video_url" 
                  type="url" 
                  placeholder="https://exemple.com/video"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                />
              </div>

              <div v-else>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Fichier vidéo
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                  <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="flex text-sm text-gray-600">
                      <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <span>Télécharger un fichier</span>
                        <input 
                          id="file-upload"
                          type="file" 
                          @change="handleFile" 
                          accept="video/*"
                          class="sr-only"
                        />
                      </label>
                      <p class="pl-1">ou glisser-déposer</p>
                    </div>
                    <p class="text-xs text-gray-500">MP4, WebM, AVI jusqu'à 100MB</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bouton submit -->
            <div class="flex justify-end gap-4 pt-4">
              <button 
  v-if="editMode"
  type="button"
  @click="resetForm"
  class="flex items-center gap-2 bg-gray-600 hover:bg-gray-400 text-white px-8 py-3 rounded-lg font-medium border-2 border-gray-500 hover:border-gray-600 shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 ease-in-out"
>
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
  </svg>
  Annuler
</button>
              <button 
                type="submit" 
                :disabled="loading"
                class="flex items-center gap-3 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                :class="editMode ? 'bg-gradient-to-r from-sky-300 to-sky-500 hover:bg-blue-600' : 'bg-gradient-to-r from-sky-300 to-sky-500'"
              >
                <svg v-if="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="editMode ? 'M5 13l4 4L19 7' : 'M12 6v6m0 0v6m0-6h6m-6 0H6'"/>
                </svg>
                {{ loading ? (editMode ? 'Modification...' : 'Ajout en cours...') : (editMode ? 'Modifier le tutoriel' : 'Ajouter le tutoriel') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Liste des tutoriels -->
    <div class="space-y-6">
  <div class="flex items-center justify-between">
    <h3 class="text-xl font-semibold text-gray-900">Tutoriels existants</h3>
    <span class="bg-blue-100 text-blue-800 px-4 py-1.5 rounded-full text-sm font-medium">
      {{ tutorials.length }} tutoriel{{ tutorials.length !== 1 ? 's' : '' }}
    </span>
  </div>

  <!-- Chargement -->
  <div v-if="loading && tutorials.length === 0" class="text-center py-12">
    <svg class="animate-spin mx-auto h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    <p class="mt-4 text-sm text-gray-500">Chargement des tutoriels...</p>
  </div>

  <!-- Aucun tutoriel -->
  <div v-else-if="tutorials.length === 0" class="text-center py-12">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun tutoriel</h3>
    <p class="mt-1 text-sm text-gray-500">Commencez par en ajouter un.</p>
    <div class="mt-6">
      <button 
        @click="isFormExpanded = true"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
      >
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Nouveau tutoriel
      </button>
    </div>
  </div>

  <!-- Grille des tutoriels -->
  <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <div 
      v-for="(tutorial, index) in tutorials" 
      :key="tutorial.id"
      class="bg-white rounded-xl shadow hover:shadow-md transition-shadow duration-300 animate-fade-in-up"
      :style="{ animationDelay: `${index * 100}ms` }"
    >
      <div class="p-6 space-y-5">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <h4 class="text-lg font-semibold text-gray-800 truncate">{{ tutorial.title }}</h4>
            <p class="text-xs text-gray-500">Ajouté le {{ new Date().toLocaleDateString() }}</p>
          </div>
          <div class="flex items-center gap-2">
            <button 
              @click="editTutorial(tutorial)"
              class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded hover:bg-yellow-100"
              title="Modifier"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L13 14l-4 1 1-4 8.5-8.5z"/>
              </svg>
            </button>
            <button 
              @click="deleteTutorial(tutorial.id)"
              class="text-red-600 hover:text-red-800 p-1.5 rounded hover:bg-red-100"
              title="Supprimer"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
          </div>
        </div>

        <div v-if="tutorial.video_url" class="aspect-w-16 aspect-h-9">
          <video 
            controls 
            class="w-full rounded-lg bg-black"
            preload="metadata"
          >
            <source :src="tutorial.video_url" type="video/mp4">
            Votre navigateur ne supporte pas la vidéo.
          </video>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-in-right {
  from { opacity: 0; transform: translateX(100px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes fade-in-up {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out;
}

.animate-slide-up {
  animation: slide-up 0.8s ease-out 0.2s both;
}

.animate-slide-in-right {
  animation: slide-in-right 0.5s ease-out;
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out both;
}

/* Aspect ratio for video */
.aspect-w-16 {
  position: relative;
  width: 100%;
}

.aspect-h-9 {
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-w-16 > * {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg,#00c6ff, #0072ff);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, #00c6ff, #0072ff);
}

/* Transitions fluides pour les éléments interactifs */
input, button, select {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de focus amélioré */
input:focus, select:focus {
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

/* Hover effects pour les cards */
.bg-white\/70:hover {
  background-color: rgba(255, 255, 255, 0.9);
}

/* Animation pour les boutons */
button:not(:disabled):active {
  transform: scale(0.95);
}

/* Style pour les vidéos */
video {
  background-color: #000;
}

video::-webkit-media-controls-panel {
  background-color: rgba(0, 0, 0, 0.8);
}

/* Responsive design amélioré */
@media (max-width: 768px) {
  .container {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  
  .text-5xl {
    font-size: 2.5rem;
  }
  
  .grid-cols-2 {
    grid-template-columns: 1fr;
  }

  .sm\:grid-cols-2 {
    grid-template-columns: 1fr;
  }

  .lg\:grid-cols-3 {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .lg\:grid-cols-3 {
    grid-template-columns: 1fr;
  }

  .flex.gap-2 {
    flex-direction: column;
    gap: 0.5rem;
  }

  .flex.gap-2 button {
    width: 100%;
    justify-content: center;
  }
}

/* Animation d'apparition pour les notifications */
@keyframes notification-slide {
  0% {
    transform: translateX(100%);
    opacity: 0;
  }
  100% {
    transform: translateX(0);
    opacity: 1;
  }
}

.animate-slide-in-right {
  animation: notification-slide 0.5s ease-out;
}

/* Amélioration de l'accessibilité */
button:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

input:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Style pour les cartes en mode sombre (optionnel) */
@media (prefers-color-scheme: dark) {
  .bg-white\/90 {
    background-color: rgba(31, 41, 55, 0.9);
    color: white;
  }
  
  .text-gray-900 {
    color: #f9fafb;
  }
  
  .text-gray-600 {
    color: #d1d5db;
  }
  
  .border-gray-100 {
    border-color: rgba(75, 85, 99, 0.3);
  }
}

/* Animation de chargement */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Effet hover pour les cartes */
.bg-white\/90:hover {
  background-color: rgba(255, 255, 255, 0.95);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Style pour les badges de type */
.badge-url {
  background: linear-gradient(135deg, #60a5fa, #3b82f6);
  color: white;
}

.badge-file {
  background: linear-gradient(135deg, #34d399, #059669);
  color: white;
}

/* Animation des boutons */
.btn-hover-effect {
  position: relative;
  overflow: hidden;
}

.btn-hover-effect::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.btn-hover-effect:hover::before {
  left: 100%;
}
</style>