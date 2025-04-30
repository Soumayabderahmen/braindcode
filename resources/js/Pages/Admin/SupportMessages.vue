<script setup>
import { ref, computed, watch } from 'vue';
//import { usePage, Link, router } from '@inertiajs/vue3';
import Main from '@/Layouts/Main.vue';
import 'bootstrap';
import '@popperjs/core';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import axios from 'axios';

import { route } from 'ziggy-js'


// Notifications
const notification = ref({
  show: false,
  message: '',
  type: 'success'
});

const showNotification = (message, type = 'success') => {
  notification.value = {
    show: true,
    message,
    type
  };
  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

// Confirm Dialog
const confirmDialog = ref({
  show: false,
  message: '',
  onConfirm: null
});

const showConfirmDialog = (message, onConfirm) => {
  confirmDialog.value = {
    show: true,
    message,
    onConfirm
  };
};

const cancelConfirmDialog = () => {
  confirmDialog.value.show = false;
};

const confirmAction = () => {
  if (confirmDialog.value.onConfirm) {
    confirmDialog.value.onConfirm();
  }
  confirmDialog.value.show = false;
};

const exportPDF = () => {
  const doc = new jsPDF();

  doc.text('Messages de Contact', 14, 16);

  const rows = filteredMessages.value.map(msg => [
    msg.name,
    msg.email,
    msg.category,
    formatDate(msg.created_at)
  ]);

  doc.autoTable({
    head: [['Nom', 'Email', 'Catégorie', 'Date']],
    body: rows,
    startY: 20,
  });

  doc.save('messages.pdf');
};

const props = defineProps({
    messages: Object
});

const localMessages = ref([...props.messages.data]);

const badgeClass = (category) => {
  return [
    'px-4 py-1 rounded-full text-white text-xs font-bold shadow-md',
    {
      'bg-gradient-to-r from-blue-400 to-blue-600': category === 'technical',
      'bg-gradient-to-r from-gray-400 to-gray-600': category === 'general',
      'bg-gradient-to-r from-pink-400 to-pink-600': category === 'Creative',
      'bg-gradient-to-r from-blue-500 to-blue-700': category === 'Premium',
      'bg-gradient-to-r from-green-400 to-green-600': category === 'Feature',
      'bg-gradient-to-r from-yellow-400 to-yellow-600': category === 'Bug',
    }
  ];
};

// État pour la recherche et la pagination
const searchQuery = ref('');
const itemsPerPage = ref(5);
const currentPage = ref(1);
const sortBy = ref('created_at');
const sortDesc = ref(true);

// Messages filtrés et triés
const filteredMessages = computed(() => {
    if (!localMessages.value) return [];
    
    let result = [...localMessages.value];
    
    // Filtrer
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(message => 
            message.name?.toLowerCase().includes(query) ||
            message.email?.toLowerCase().includes(query) ||
            message.category?.toLowerCase().includes(query)
        );
    }
    
    // Trier les résultats
    result.sort((a, b) => {
        let modifier = sortDesc.value ? -1 : 1;
        if (a[sortBy.value] < b[sortBy.value]) return -1 * modifier;
        if (a[sortBy.value] > b[sortBy.value]) return 1 * modifier;
        return 0;
    });
    
    return result;
});

// Pagination
const paginatedMessages = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredMessages.value.slice(start, end);
});
const user = ref(window.authUser);

// Nombre total de pages
const totalPages = computed(() => {
    return Math.ceil(filteredMessages.value.length / itemsPerPage.value) || 1;
});

// Pages à afficher
const displayPages = computed(() => {
    let pages = [];
    const maxPages = Math.min(totalPages.value, 5);
    
    // Calculer la plage de pages à afficher
    let startPage = Math.max(1, currentPage.value - 2);
    let endPage = startPage + maxPages - 1;
    
    if (endPage > totalPages.value) {
        endPage = totalPages.value;
        startPage = Math.max(1, endPage - maxPages + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }
    
    return pages;
});

// Réinitialiser la page lorsque la recherche change
watch(searchQuery, () => {
    currentPage.value = 1;
});

// Changement de page
const changePage = (page) => {
    currentPage.value = page;
};

// Changement du tri
const changeSort = (column) => {
    if (sortBy.value === column) {
        sortDesc.value = !sortDesc.value;
    } else {
        sortBy.value = column;
        sortDesc.value = true;
    }
};

// Supprimer un message
const deleteMessage = (id) => {
  showConfirmDialog('Êtes-vous sûr de vouloir supprimer ce message ?', () => {
    axios.delete(route('admin.support.messages.delete', { supportMessage: id }))
      .then(response => {
        // Supprimer proprement dans le local
        const index = localMessages.value.findIndex(m => m.id === id);
        if (index !== -1) {
          localMessages.value.splice(index, 1);
        }
        showNotification('Message supprimé avec succès', 'warning');
      })
      .catch(error => {
        console.error('Erreur lors de la suppression', error);
        showNotification('Erreur lors de la suppression', 'error');
      });
  });
};







// Formatage de date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  });
};


const exportCSV = () => {
  const headers = ["Nom", "Email", "Catégorie", "Date"];
  const rows = filteredMessages.value.map(msg => [
    msg.name,
    msg.email,
    msg.category,
    formatDate(msg.created_at)
  ]);

  let csvContent = "data:text/csv;charset=utf-8," + [headers, ...rows].map(e => e.join(",")).join("\n");

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "messages.csv");
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

</script>

<template>
  <!-- Notification -->
<div
  v-if="notification.show"
  :class="[
    'fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform',
    notification.type === 'success' ? 'bg-green-600' : notification.type === 'warning' ? 'bg-amber-600' : 'bg-red-600'
  ]"
  style="min-width: 300px"
>
  <div class="flex items-center">
    <span class="font-medium">{{ notification.message }}</span>
  </div>
</div>

<!-- Confirm Dialog -->
<div v-if="confirmDialog.show" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full">
    <div class="text-center p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmation</h3>
      <p class="text-gray-600">{{ confirmDialog.message }}</p>
    </div>
    <div class="flex justify-end space-x-3 mt-6">
      <button @click="cancelConfirmDialog" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
        Annuler
      </button>
      <button @click="confirmAction" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
        Confirmer
      </button>
    </div>
  </div>
</div>

      <div class="p-6  min-h-screen flex justify-center">
        <div class="bg-white shadow rounded-lg p-6 w-full max-w-7xl">
          <!-- Header (Titre + Export) -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">📄 Messages de Contact</h1>
  
            <div class="flex gap-4">
  <button @click="exportCSV"
    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
    📤 Exporter CSV
  </button>

  <button @click="exportPDF"
    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-500 hover:bg-red-600">
    🖨️ Exporter PDF
  </button>
</div>

          </div>
  
          <!-- Top Controls (Recherche + Items per page) -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-700">Afficher</span>
              <select v-model="itemsPerPage"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
              </select>
              <span class="text-sm text-gray-700">par page</span>
            </div>
  
            <div class="relative w-full md:w-64">
              <input type="text" v-model="searchQuery" placeholder="Rechercher..."
                class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500" />
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>
  
          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th @click="changeSort('name')" class="sortable">Nom</th>
                  <th @click="changeSort('email')" class="sortable">Email</th>
                  <th @click="changeSort('category')" class="sortable">Catégorie</th>
                  <th @click="changeSort('created_at')" class="sortable">Date</th>
                  <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-3">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="message in paginatedMessages" :key="message.id"
                  class="hover:scale-[1.02] transition-all duration-200 hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ message.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ message.email }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="badgeClass(message.category)">
                      {{ message.category }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(message.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
  <div class="flex gap-2">
    <!-- Voir (équivalent à "edit" ici) -->
    <a :href="route('admin.support.message.view', { id: message.id })"
      class="btn btn-icon btn-sm btn-info-transparent rounded-pill">
      <i class="ri-eye-line text-info"></i>
  </a>

    <!-- Supprimer -->
    <button @click="deleteMessage(message.id)"
      class="btn btn-icon btn-sm btn-danger-transparent rounded-pill">
      <i class="ri-delete-bin-line text-danger"></i>
    </button>
  </div>
</td>

                </tr>
  
                <tr v-if="paginatedMessages.length === 0">
                  <td colspan="5" class="text-center py-4 text-gray-500">Aucun message trouvé</td>
                </tr>
              </tbody>
            </table>
          </div>
  
          <!-- Pagination -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-6">
            <div class="text-sm text-gray-600">
              Affichage de
              <span class="font-semibold">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
              à
              <span class="font-semibold">{{ Math.min(currentPage * itemsPerPage, filteredMessages.length) }}</span>
              sur
              <span class="font-semibold">{{ filteredMessages.length }}</span> résultats
            </div>
  
            <div class="mt-4 md:mt-0">
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                  class="pagination-btn rounded-l-md">Précédent</button>
                <button v-for="page in displayPages" :key="page" @click="changePage(page)"
                  :class="page === currentPage ? 'pagination-btn-active' : 'pagination-btn'">
                  {{ page }}
                </button>
                <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                  class="pagination-btn rounded-r-md">Suivant</button>
              </nav>
            </div>
          </div>
  
        </div>
      </div>
   
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

  </template>
  
  

<style scoped>
.btn-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 9999px;
  transition: all 0.2s ease;
}

.btn-sm {
  font-size: 0.875rem;
}

.btn-info-transparent {
  background-color: #e0f2ff;
}

.btn-danger-transparent {
  background-color: #ffe4e6;
}

.btn-success-transparent {
  background-color: #dcfce7;
}

.btn:hover {
  filter: brightness(0.95);
}
  .sortable {
    cursor: pointer;
    text-align: left;
    padding: 12px 24px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #6B7280;
  }
  .pagination-btn {
    background: white;
    border: 1px solid #d1d5db;
    padding: 6px 12px;
    font-size: 0.875rem;
    color: #6B7280;
  }
  .pagination-btn:hover {
    background: #f9fafb;
  }
  .pagination-btn-active {
    background: #3b82f6;
    color: white;
    border: 1px solid #3b82f6;
    padding: 6px 12px;
    font-size: 0.875rem;
  }
  </style>
  