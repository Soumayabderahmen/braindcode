<!-- FaqAdmin.vue -->
<script setup>
import { ref, computed } from 'vue'
// Remplacer router par axios pour les requêtes HTTP
import axios from 'axios'
import { useForm } from '@inertiajs/vue3'
import { onMounted, watchEffect } from 'vue'

const props = defineProps({
  faqs: Array
})

const showModal = ref(false)
const editingFaq = ref(null)
const searchTerm = ref('')
const isLoading = ref(false)
const localFaqs = ref(props.faqs || [])

// Animation states
const tableLoaded = ref(false)

// Filtre de recherche
const filteredFaqs = computed(() => {
  if (!searchTerm.value) return localFaqs.value
  
  const term = searchTerm.value.toLowerCase()
  return localFaqs.value.filter(faq => 
    faq.question.toLowerCase().includes(term) || 
    faq.answer.toLowerCase().includes(term)
  )
})

const form = useForm({
  question: '',
  answer: '',
  is_active: true
})

onMounted(() => {
  // Simuler un chargement pour effet visuel
  isLoading.value = true
  setTimeout(() => {
    isLoading.value = false
    tableLoaded.value = true
  }, 300)
})

const openModal = (faq = null) => {
  showModal.value = true
  if (faq) {
    editingFaq.value = faq
    form.question = faq.question
    form.answer = faq.answer
    form.is_active = faq.is_active
  } else {
    editingFaq.value = null
    form.reset()
    form.is_active = true
  }
}

const closeModal = () => {
  showModal.value = false
  form.reset()
  editingFaq.value = null
}

const submit = () => {
  if (editingFaq.value) {
    // Utiliser axios pour l'update
    axios.put(`/admin/faqs/${editingFaq.value.id}`, {
      question: form.question,
      answer: form.answer,
      is_active: form.is_active
    })
    .then(response => {
      // Mettre à jour le faq dans le tableau local
      const index = localFaqs.value.findIndex(f => f.id === editingFaq.value.id)
      if (index !== -1) {
        localFaqs.value[index] = {
          ...localFaqs.value[index],
          question: form.question,
          answer: form.answer,
          is_active: form.is_active
        }
      }
      showNotification('FAQ mise à jour avec succès')
      closeModal()
    })
    .catch(error => {
      console.error('Erreur lors de la mise à jour', error)
      // Gérer les erreurs de validation si nécessaire
      if (error.response && error.response.data && error.response.data.errors) {
        form.errors = error.response.data.errors
      }
    })
  } else {
    // Utiliser axios pour la création
    axios.post('/admin/faqs', {
      question: form.question,
      answer: form.answer,
      is_active: form.is_active
    })
    .then(response => {
      // Ajouter le nouveau faq au tableau local
      localFaqs.value.push(response.data.faq || {
        id: Date.now(), // Temporaire en attendant la réponse du serveur
        question: form.question,
        answer: form.answer,
        is_active: form.is_active
      })
      showNotification('FAQ ajoutée avec succès')
      closeModal()
    })
    .catch(error => {
      console.error('Erreur lors de la création', error)
      // Gérer les erreurs de validation si nécessaire
      if (error.response && error.response.data && error.response.data.errors) {
        form.errors = error.response.data.errors
      }
    })
  }
}

const deleteFaq = (id) => {
  showConfirmDialog('Êtes-vous sûr de vouloir supprimer cette FAQ?', () => {
    // Utiliser axios pour la suppression
    axios.delete(`/admin/faqs/${id}`)
    .then(response => {
      // Supprimer le faq du tableau local
      localFaqs.value = localFaqs.value.filter(faq => faq.id !== id)
      showNotification('FAQ supprimée avec succès', 'warning')
    })
    .catch(error => {
      console.error('Erreur lors de la suppression', error)
    })
  })
}

// États pour les notifications
const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const showNotification = (message, type = 'success') => {
  notification.value = {
    show: true,
    message,
    type
  }
  
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

// État pour la boîte de dialogue de confirmation
const confirmDialog = ref({
  show: false,
  message: '',
  onConfirm: null
})

const showConfirmDialog = (message, onConfirm) => {
  confirmDialog.value = {
    show: true,
    message,
    onConfirm
  }
}

const cancelConfirmDialog = () => {
  confirmDialog.value.show = false
}

const confirmAction = () => {
  if (confirmDialog.value.onConfirm) {
    confirmDialog.value.onConfirm()
  }
  confirmDialog.value.show = false
}
</script>

<template>
  <div class="p-6 min-h-screen">
    <!-- Notification toast -->
    <div
      v-if="notification.show"
      :class="[
        'fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform',
        notification.type === 'success' ? 'bg-green-600' : 'bg-amber-600'
      ]"
      style="min-width: 300px"
    >
      <div class="flex items-center">
        <div v-if="notification.type === 'success'" class="mr-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
        <div v-else class="mr-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        </div>
        <span class="font-medium">{{ notification.message }}</span>
      </div>
    </div>

    <!-- Confirm Dialog -->
    <div
      v-if="confirmDialog.show"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full animate-bounce-in">
        <div class="text-center p-6">
          <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmation</h3>
          <p class="text-gray-600">{{ confirmDialog.message }}</p>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
          <button
            @click="cancelConfirmDialog"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition"
          >
            Annuler
          </button>
          <button
            @click="confirmAction"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
          >
            Confirmer
          </button>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Gestion des FAQs
        </h1>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
          <div class="relative flex-grow">
            <input 
              type="text" 
              v-model="searchTerm" 
              placeholder="Rechercher..." 
              class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
          <button 
            @click="openModal()" 
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition shadow-md whitespace-nowrap"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Ajouter une FAQ
          </button>
        </div>
      </div>

      <!-- État de chargement -->
      <div v-if="isLoading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>

      <!-- Table moderne -->
      <div v-else class="overflow-hidden rounded-xl border border-gray-200">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réponse</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="filteredFaqs.length === 0" class="hover:bg-gray-50">
                <td colspan="4" class="px-6 py-10 text-sm text-center text-gray-500">
                  <div class="flex flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-lg font-medium">Aucune FAQ trouvée</span>
                    <span v-if="searchTerm" class="text-sm text-gray-500 mt-1">Essayez avec un autre terme de recherche</span>
                  </div>
                </td>
              </tr>
              <tr 
                v-for="(faq, index) in filteredFaqs" 
                :key="faq.id" 
                class="hover:bg-gray-50 transition-colors duration-150"
                :class="tableLoaded ? 'animate-fade-in-up' : ''"
                :style="{ animationDelay: `${index * 50}ms` }"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ faq.question }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  <div class="truncate max-w-xs">{{ faq.answer }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span 
                    :class="[
                      'px-2 py-1 inline-flex text-xs leading-5 rounded-full',
                      faq.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-gray-800'
                    ]"
                  >
                    {{ faq.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <div class="flex space-x-2">
                    <button 
                      @click="openModal(faq)" 
                      class="text-blue-600 hover:text-blue-900 transition focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md p-1"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                      </svg>
                    </button>
                    <button 
                      @click="deleteFaq(faq.id)" 
                      class="text-red-600 hover:text-red-900 transition focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md p-1"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-0 relative animate-zoom-in">
        <!-- Header -->
        <div class="bg-gray-50 px-8 py-4 rounded-t-xl border-b">
          <h2 class="text-xl font-semibold text-gray-800">
            {{ editingFaq ? 'Modifier une FAQ' : 'Créer une nouvelle FAQ' }}
          </h2>
        </div>
        
        <!-- Body -->
        <div class="px-8 py-6">
          <div class="grid grid-cols-1 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
              <input
                type="text"
                v-model="form.question"
                placeholder="Entrez la question"
                class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none shadow-sm"
              />
              <div v-if="form.errors.question" class="text-sm text-red-500 mt-1">
                {{ form.errors.question }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Réponse</label>
              <textarea
                v-model="form.answer"
                placeholder="Entrez la réponse"
                rows="5"
                class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none shadow-sm"
              ></textarea>
              <div v-if="form.errors.answer" class="text-sm text-red-500 mt-1">
                {{ form.errors.answer }}
              </div>
            </div>

            <div class="flex items-center mt-2">
              <div class="relative inline-block w-10 mr-2 align-middle select-none">
                <input 
                  type="checkbox" 
                  id="is_active" 
                  v-model="form.is_active" 
                  class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer transition-transform duration-300 ease-in-out"
                />
                <label 
                  for="is_active" 
                  class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                ></label>
              </div>
              <label for="is_active" class="text-sm font-medium text-gray-700">Activer cette FAQ</label>
            </div>
          </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 rounded-b-xl border-t flex justify-end space-x-3">
          <button
            @click="closeModal"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
          >
            Annuler
          </button>
          <button
            @click="submit"
            :disabled="form.processing"
            :class="[
              'px-5 py-2 text-white rounded-md transition focus:outline-none focus:ring-2 focus:ring-offset-2',
              form.processing ? 'bg-blue-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
            ]"
          >
            <div class="flex items-center">
              <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ editingFaq ? 'Mettre à jour' : 'Ajouter' }}
            </div>
          </button>
        </div>
        
        <!-- Close button -->
        <button 
          @click="closeModal" 
          class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes zoom-in {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes bounce-in {
  0% {
    opacity: 0;
    transform: scale(0.8);
  }
  70% {
    transform: scale(1.05);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.3s ease-out forwards;
}

.animate-zoom-in {
  animation: zoom-in 0.3s ease-out;
}

.animate-bounce-in {
  animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Toggle Switch */
.toggle-checkbox:checked {
  transform: translateX(100%);
  border-color: #3b82f6;
}
.toggle-checkbox:checked + .toggle-label {
  background-color: #93c5fd;
}
</style>