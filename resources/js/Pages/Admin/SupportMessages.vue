<script setup>
import { ref, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import axios from 'axios';

const props = defineProps({
  messages: Object,
});

const selectedMessage = ref(null);
const localMessages = ref([...props.messages.data]);
const showDeleteModal = ref(false);
const showMarkAsModal = ref(false);
const pendingStatus = ref('');
const isLoading = ref(false);
const notification = ref({ show: false, message: '', type: '' });
const searchQuery = ref('');

// Sélection d'un message
const selectMessage = (message) => {
  selectedMessage.value = message;
  // Marquer automatiquement comme lu si c'est un nouveau message
  if (message.status === 'new') {
    markAs('read', false);
  }
};

// Copier l'email avec notification
const copyEmail = (email) => {
  navigator.clipboard.writeText(email);
  showNotification('Email copié avec succès', 'success');
};

// Afficher la modale de confirmation pour changer de statut
const confirmMarkAs = (status) => {
  pendingStatus.value = status;
  showMarkAsModal.value = true;
};

// Fonction pour changer le statut d'un message
const markAs = (status, showConfirmation = true) => {
  if (!selectedMessage.value) return;
  
  if (showConfirmation && status !== 'read') {
    confirmMarkAs(status);
    return;
  }
  
  isLoading.value = true;
  
  axios.put(route('admin.support.message.status', { id: selectedMessage.value.id }), {
    status
  })
  .then(() => {
    selectedMessage.value.status = status;

    const index = localMessages.value.findIndex(
      (msg) => msg.id === selectedMessage.value.id
    );

    if (index !== -1) {
      localMessages.value[index].status = status;
    }
    
    showNotification(`Message marqué comme "${statusLabel(status)}"`, 'success');
    showMarkAsModal.value = false;
  })
  .catch((error) => {
    console.error("Erreur lors du changement de statut", error);
    showNotification("Erreur lors du changement de statut", 'error');
  })
  .finally(() => {
    isLoading.value = false;
  });
};

// Afficher la modale de confirmation pour la suppression
const confirmDelete = () => {
  showDeleteModal.value = true;
};

// Fonction de suppression
const deleteMessage = () => {
  if (!selectedMessage.value) return;

  isLoading.value = true;
  
  axios
    .delete(route('admin.support.messages.delete', { supportMessage: selectedMessage.value.id }))
    .then(() => {
      const index = localMessages.value.findIndex(
        (msg) => msg.id === selectedMessage.value.id
      );

      if (index !== -1) {
        localMessages.value.splice(index, 1);
      }

      selectedMessage.value = null;
      showNotification('Message supprimé avec succès', 'success');
      showDeleteModal.value = false;
    })
    .catch((error) => {
      console.error('Erreur lors de la suppression', error);
      showNotification('Erreur lors de la suppression', 'error');
    })
    .finally(() => {
      isLoading.value = false;
    });
};

// Formatage de la date
const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Vérifier si un message est sélectionné
const isSelected = (message) => {
  return selectedMessage.value && selectedMessage.value.id === message.id;
};

// Filtrage par statut
const filterStatus = ref('all');
const filteredMessages = computed(() => {
  let filtered = localMessages.value;
  
  // Filtrer par statut
  if (filterStatus.value !== 'all') {
    filtered = filtered.filter(m => m.status === filterStatus.value);
  }
  
  // Filtrer par recherche
  if (searchQuery.value.trim() !== '') {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(m => 
      m.name.toLowerCase().includes(query) || 
      m.email.toLowerCase().includes(query) ||
      m.message.toLowerCase().includes(query) ||
      m.category.toLowerCase().includes(query)
    );
  }
  
  return filtered;
});

// Classes pour les boutons de filtre
const statusButtonClass = (status) => {
  return [
    "px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200",
    filterStatus.value === status
      ? "bg-blue-600 text-white shadow-md"
      : "bg-gray-100 text-gray-700 hover:bg-gray-200"
  ];
};

// Obtenir le label d'un statut
const statusLabel = (status) => {
  switch (status) {
    case 'new': return 'Nouveau';
    case 'read': return 'Lu';
    case 'replied': return 'Répondu';
    default: return status;
  }
};

// Badge de statut avec couleur
const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'new': return 'bg-blue-100 text-blue-800';
    case 'read': return 'bg-gray-100 text-gray-800';
    case 'replied': return 'bg-green-100 text-green-800';
    default: return 'bg-gray-100 text-gray-600';
  }
};

// Afficher une notification
const showNotification = (message, type = 'info') => {
  notification.value = {
    show: true,
    message,
    type
  };
  
  // Masquer automatiquement après 3 secondes
  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

// Vérifier s'il y a des messages
const hasMessages = computed(() => {
  return filteredMessages.value.length > 0;
});

// Style pour la notification
const notificationStyle = computed(() => {
  return {
    'bg-green-100 border-green-500 text-green-700': notification.value.type === 'success',
    'bg-red-100 border-red-500 text-red-700': notification.value.type === 'error',
    'bg-blue-100 border-blue-500 text-blue-700': notification.value.type === 'info'
  };
});

// Fonction pour fermer les modales
const closeModals = () => {
  showDeleteModal.value = false;
  showMarkAsModal.value = false;
};

// Classe d'icône pour le statut
const getStatusIcon = (status) => {
  switch (status) {
    case 'new': return '🔵';
    case 'read': return '👁️';
    case 'replied': return '✅';
    default: return '❓';
  }
};
</script>

<template>
 
    <!-- En-tête avec recherche et filtres -->
   
      <div class="container mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <h1 class="text-xl font-bold text-blue-800"></h1>
          
          <!-- Recherche -->
          <div class="relative flex-grow md:max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <input 
              v-model="searchQuery" 
              type="search" 
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Rechercher un message..."
            >
          </div>
        </div>
        
        <!-- Filtres par statut -->
        <div class="flex flex-wrap gap-2 mt-4">
          <button @click="filterStatus = 'all'" :class="statusButtonClass('all')">
            Tous
          </button>
          <button @click="filterStatus = 'new'" :class="statusButtonClass('new')">
            <span class="inline-flex items-center">
              <span class="w-2 h-2 rounded-full bg-blue-600 mr-2"></span>
              Nouveaux
            </span>
          </button>
          <button @click="filterStatus = 'read'" :class="statusButtonClass('read')">
            <span class="inline-flex items-center">
              <span class="w-2 h-2 rounded-full bg-gray-600 mr-2"></span>
              Lus
            </span>
          </button>
          <button @click="filterStatus = 'replied'" :class="statusButtonClass('replied')">
            <span class="inline-flex items-center">
              <span class="w-2 h-2 rounded-full bg-green-600 mr-2"></span>
              Répondus
            </span>
          </button>
        </div>
      </div>
   
    
    <!-- Contenu principal -->
    <div class="flex flex-1 overflow-hidden shadow-lg rounded-lg m-4 bg-white border">
      <!-- Liste des messages -->
      <div class="w-full md:w-1/3 border-r overflow-y-auto">
        <div v-if="hasMessages" class="divide-y divide-gray-200">
          <div
            v-for="message in filteredMessages"
            :key="message.id"
            @click="selectMessage(message)"
            class="p-4 cursor-pointer transition-all duration-150 border-l-4"
            :class="[
              isSelected(message) 
                ? 'bg-blue-50 border-l-blue-600' 
                : 'hover:bg-gray-50 border-l-transparent',
              message.status === 'new' ? 'font-medium' : ''
            ]"
          >
            <div class="flex justify-between items-start">
              <h3 class="font-semibold">{{ message.name }}</h3>
              <span :class="['text-xs px-2 py-1 rounded-full whitespace-nowrap', getStatusBadgeClass(message.status)]">
                {{ getStatusIcon(message.status) }} {{ statusLabel(message.status) }}
              </span>
            </div>
            <p class="text-xs text-blue-600 mt-1">{{ message.category }}</p>
            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ message.message }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ formatDate(message.created_at) }}</p>
          </div>
        </div>
        <div v-else class="flex flex-col items-center justify-center h-64 text-gray-500">
          <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
          </svg>
          <p class="text-center">Aucun message trouvé</p>
        </div>
      </div>

      <!-- Détails du message -->
      <div class="hidden md:flex md:flex-col md:w-2/3 p-6 overflow-y-auto">
        <div v-if="selectedMessage" class="flex flex-col h-full">
          <!-- En-tête du message -->
          <div class="flex justify-between items-start pb-4 border-b">
            <div>
              <h2 class="text-xl font-semibold mb-1">{{ selectedMessage.category }}</h2>
              <p class="text-sm">De : <span class="font-medium">{{ selectedMessage.name }}</span></p>
              <p class="text-sm text-blue-600 hover:underline cursor-pointer" @click="copyEmail(selectedMessage.email)">
                {{ selectedMessage.email }} <span class="text-xs">(cliquer pour copier)</span>
              </p>
              <p class="text-xs text-gray-500 mt-1">Reçu le {{ formatDate(selectedMessage.created_at) }}</p>
            </div>
            <div class="flex items-center">
              <button 
                @click="copyEmail(selectedMessage.email)" 
                class="flex items-center text-sm bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                </svg>
                Copier l'email
              </button>
            </div>
          </div>
          
          <!-- Corps du message -->
          <div class="flex-grow mt-4 overflow-y-auto">
            <div class="bg-gray-50 p-6 rounded-lg border">
              <p class="whitespace-pre-wrap text-sm leading-relaxed text-gray-800">{{ selectedMessage.message }}</p>
            </div>
            
            <!-- Pièce jointe -->
            <div v-if="selectedMessage.file_path" class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-100 flex items-center">
              <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
              </svg>
              <a :href="route('admin.support.download', selectedMessage.id)" class="text-blue-600 hover:text-blue-800 font-medium">
  Télécharger la pièce jointe
</a>
            </div>
          </div>
          
          <!-- Actions sur le message -->
          <div class="mt-6 flex flex-wrap gap-3 pt-4 border-t">
            <button 
              @click="markAs('read')" 
              class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
              :disabled="selectedMessage.status === 'read'">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              Marquer comme Lu
            </button>
            <button 
              @click="confirmMarkAs('replied')" 
              class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
              :disabled="selectedMessage.status === 'replied'">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
              </svg>
              Marquer comme Répondu
            </button>
            <button 
              @click="confirmDelete" 
              class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors ml-auto">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
              Supprimer
            </button>
          </div>
        </div>
        
        <!-- Aucun message sélectionné -->
        <div v-else class="flex flex-col items-center justify-center h-full text-gray-500">
          <svg class="w-24 h-24 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
          </svg>
          <p class="text-lg font-medium">Sélectionnez un message</p>
          <p class="text-sm text-center mt-2 max-w-md">
            Choisissez un message dans la liste pour voir son contenu et effectuer des actions
          </p>
        </div>
      </div>
    </div>
    
    <!-- Notification -->
    <div 
      v-if="notification.show" 
      class="fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg border-l-4 transition-all duration-300"
      :class="notificationStyle">
      <div class="flex items-center">
        <p>{{ notification.message }}</p>
        <button @click="notification.show = false" class="ml-4 text-gray-600 hover:text-gray-800">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Modal de suppression -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 mx-4" @click.stop>
        <div class="text-center mb-4">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
          <p class="text-sm text-gray-500 mt-2">
            Êtes-vous sûr de vouloir supprimer ce message ? Cette action ne peut pas être annulée.
          </p>
        </div>
        <div class="flex justify-end gap-3 mt-5">
          <button 
            @click="closeModals" 
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
            Annuler
          </button>
          <button 
            @click="deleteMessage" 
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all"
            :disabled="isLoading">
            <span v-if="isLoading" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Traitement...
            </span>
            <span v-else>Supprimer</span>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Modal de changement de statut -->
    <div v-if="showMarkAsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 mx-4" @click.stop>
        <div class="text-center mb-4">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900">Changer le statut</h3>
          <p class="text-sm text-gray-500 mt-2">
            Voulez-vous vraiment marquer ce message comme "{{ statusLabel(pendingStatus) }}" ?
          </p>
        </div>
        <div class="flex justify-end gap-3 mt-5">
          <button 
            @click="closeModals" 
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
            Annuler
          </button>
          <button 
            @click="markAs(pendingStatus, false)" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all"
            :disabled="isLoading">
            <span v-if="isLoading" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Traitement...
            </span>
            <span v-else>Confirmer</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Vue mobile pour le message sélectionné -->
    <div 
      v-if="selectedMessage" 
      class="fixed inset-0 bg-white z-40 md:hidden overflow-y-auto">
      <div class="flex flex-col h-full">
        <!-- En-tête mobile -->
        <div class="bg-white shadow-sm p-4 flex justify-between items-center border-b">
          <button @click="selectedMessage = null" class="text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
          </button>
          <h2 class="text-lg font-medium">Détails du message</h2>
          <div></div>
        </div>
        
        <!-- Contenu du message mobile -->
        <div class="p-4 flex-grow overflow-y-auto">
          <div>
            <h2 class="text-xl font-semibold mb-1">{{ selectedMessage.category }}</h2>
            <p class="text-sm">De : <span class="font-medium">{{ selectedMessage.name }}</span></p>
            <p class="text-sm text-blue-600">{{ selectedMessage.email }}</p>
            <p class="text-xs text-gray-500 mt-1">Reçu le {{ formatDate(selectedMessage.created_at) }}</p>
          </div>
          
          <div class="mt-4 bg-gray-50 p-4 rounded-lg border">
            <p class="whitespace-pre-wrap text-sm leading-relaxed">{{ selectedMessage.message }}</p>
          </div>
          
          <div v-if="selectedMessage.file_path" class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-100 flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
            </svg>
            <a :href="'/storage/' + selectedMessage.file_path" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">
              Télécharger la pièce jointe
            </a>
          </div>
          
          <!-- Actions en mobile -->
          <div class="mt-6 grid grid-cols-2 gap-3">
            <button 
              @click="copyEmail(selectedMessage.email)" 
              class="flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
              </svg>
              Copier l'email
            </button>
            <button 
              @click="markAs('read')" 
              class="flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
              :disabled="selectedMessage.status === 'read'">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              Marquer comme Lu
            </button>
            <button 
              @click="confirmMarkAs('replied')" 
              class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
              :disabled="selectedMessage.status === 'replied'">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
              </svg>
              Marquer comme Répondu
            </button>
            <button 
              @click="confirmDelete" 
              class="flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
 
</template>

<style scoped>
/* Animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { transform: translateY(10px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

/* Animation des cartes de message */
[v-for].divide-y > div {
  animation: slideIn 0.2s ease-out;
}

/* Animation des notifications */
[v-if="notification.show"] {
  animation: fadeIn 0.3s ease-out;
}

/* Ligne tronquée */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}

/* Style pour les boutons désactivés */
button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>