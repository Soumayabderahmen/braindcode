<template>
 
    <div style="margin: 3%;">
      <h2 class="dashboard-title">Statistiques des Réactions</h2>
      
      <!-- Statistiques -->
      <div class="stats-grid">
        <div class="stat-card positive">
          <div class="stat-icon">👍</div>
          <div class="stat-content">
            <div class="stat-label">Likes</div>
            <div class="stat-value">{{ totalLikes }}</div>
          </div>
        </div>
        
        <div class="stat-card negative">
          <div class="stat-icon">👎</div>
          <div class="stat-content">
            <div class="stat-label">Dislikes</div>
            <div class="stat-value">{{ totalDislikes }}</div>
          </div>
        </div>
      </div>
      
      <!-- Filtres -->
      <div class="filters-container">
        <div class="search-wrapper">
          <input v-model="searchText" placeholder="Rechercher un message..." class="search-input">
          <i class="search-icon">🔍</i>
        </div>

        <div class="filters-group">
          <select v-model="selectedUser" class="filter-select">
            <option value="">Tous les utilisateurs</option>
            <option v-for="u in uniqueUsers" :key="u">{{ u }}</option>
          </select>

          <select v-model="selectedReactionType" class="filter-select">
            <option value="">Toutes les réactions</option>
            <option value="👍">👍 Likes</option>
            <option value="👎">👎 Dislikes</option>
          </select>

          <select v-model="itemsPerPage" class="filter-select">
            <option value="5">5 par page</option>
            <option value="10">10 par page</option>
            <option value="20">20 par page</option>
          </select>

          <button class="reset-button" @click="resetFilters">
            <span class="reset-icon">↺</span>
            <span>Réinitialiser</span>
          </button>
        </div>
      </div>

      <!-- Tableau des données -->
      <div class="data-table">
        <!-- En-tête du tableau -->
        <div class="table-header">
          <div class="header-user">Utilisateur</div>
          <div class="header-message">Message</div>
          <div class="header-reaction">Réaction</div>
          <div class="header-date">Date</div>
        </div>

        <!-- Données -->
        <div v-for="(r, i) in paginatedReactions" :key="i" class="data-row">
          <div class="user-cell">
            <div class="avatar" :style="{ backgroundColor: getUserColor(r.user?.name) }">
              {{ getInitial(r.user?.name || "U") }}
            </div>
            <span class="username">{{ r.user?.name || "Utilisateur inconnu" }}</span>
          </div>

          <div class="message-cell" @click="openModal(r)">
            {{ truncate(r.message, 60) }}
          </div>

          <div class="reaction-cell" @click="r.reaction === '👎' && r.user ? openDislikeModal(r) : null">
            <span :class="['reaction-badge', r.reaction === '👍' ? 'like-badge' : 'dislike-badge']">
              {{ r.reaction }}
            </span>
          </div>

          <div class="date-cell">
            {{ formatDate(r.created_at) }}
          </div>
        </div>
      </div>

      <!-- État vide -->
      <div v-if="filteredReactions.length === 0" class="empty-state">
        <div class="empty-icon">🔍</div>
        <p>Aucune réaction ne correspond à ces critères.</p>
        <button class="empty-button" @click="resetFilters">Réinitialiser les filtres</button>
      </div>

      <!-- Pagination -->
      <div class="pagination" v-if="totalPages > 1">
        <button class="page-button prev" :disabled="currentPage === 1" @click="currentPage--">
          <span class="page-arrow">‹</span>
        </button>

        <div class="page-numbers">
          <button v-for="page in displayedPages" :key="page" 
                  :class="['page-number', { active: currentPage === page }]" 
                  @click="currentPage = page">
            {{ page }}
          </button>
        </div>

        <button class="page-button next" :disabled="currentPage === totalPages" @click="currentPage++">
          <span class="page-arrow">›</span>
        </button>
      </div>
    </div>

    <!-- Modal détail -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3>Détail de la réaction</h3>
          <button class="modal-close" @click="closeModal">×</button>
        </div>
        <div class="modal-body">
          <div class="detail-row">
            <div class="detail-label">Utilisateur</div>
            <div class="detail-value">{{ selectedReaction?.user?.name || 'Utilisateur inconnu' }}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Message</div>
            <div class="detail-value message-detail">{{ selectedReaction?.message }}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Réaction</div>
            <div class="detail-value">
              <span :class="['reaction-badge', selectedReaction?.reaction === '👍' ? 'like-badge' : 'dislike-badge']">
                {{ selectedReaction?.reaction }}
              </span>
            </div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Date</div>
            <div class="detail-value">{{ formatDate(selectedReaction?.created_at) }}</div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="modal-button" @click="closeModal">Fermer</button>
        </div>
      </div>
    </div>

    <!-- Modal Dislike -->
    <div v-if="showDislikeModal" class="modal-overlay" @click.self="closeDislikeModal">
      <div class="modal-content dislike-modal">
        <div class="modal-header dislike-header">
          <h3>Message signalé 👎</h3>
          <button class="modal-close" @click="closeDislikeModal">×</button>
        </div>
        <div class="modal-body">
          <div class="detail-row">
            <div class="detail-label">Utilisateur</div>
            <div class="detail-value">{{ selectedDislike?.user?.name || 'Utilisateur inconnu' }}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Message utilisateur</div>
            <div class="detail-value user-message">
              {{ selectedDislike?.originalMessage || 'Message original non disponible.' }}
            </div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Réponse du bot</div>
            <div class="detail-value bot-response">{{ selectedDislike?.message }}</div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="modal-button" @click="closeDislikeModal">Fermer</button>
        </div>
      </div>
    </div>
 
</template>

<script setup>
import { ref, computed } from 'vue'
//import { usePage } from '@inertiajs/vue3'
//import Main from '@/Layouts/Main.vue'

// États
const selectedReactionType = ref("")
const selectedUser = ref("")
const searchText = ref("")
const currentPage = ref(1)
const itemsPerPage = ref(5)

// Modals
const showModal = ref(false)
const selectedReaction = ref(null)
const showDislikeModal = ref(false)
const selectedDislike = ref(null)

// Props
const props = defineProps({
  reactions: {
    type: Array,
    required: true
  }
})


// Données
const reactions = computed(() => {
  const data = props.reactions || []
  return data.map(r => ({
    ...r,
    originalMessage: r.originalMessage || "Message utilisateur simulé"
  }))
})

// Utilitaires
const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  return date.toLocaleDateString("fr-FR", { 
    day: "2-digit", 
    month: "2-digit", 
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit"
  })
}

const getInitial = (name) => name?.charAt(0).toUpperCase() || 'U'
const truncate = (text, len) => (text?.length > len ? text.slice(0, len) + "..." : text)

// Génère une couleur cohérente basée sur le nom d'utilisateur
const getUserColor = (name) => {
  if (!name) return '#6366f1' // Couleur par défaut
  
  let hash = 0
  for (let i = 0; i < name.length; i++) {
    hash = name.charCodeAt(i) + ((hash << 5) - hash)
  }
  
  const colors = [
    '#6366f1', // Indigo
    '#8b5cf6', // Violet
    '#ec4899', // Rose
    '#0ea5e9', // Sky
    '#10b981', // Emerald
    '#14b8a6', // Teal
    '#f59e0b', // Amber
    '#f97316'  // Orange
  ]
  
  return colors[Math.abs(hash) % colors.length]
}

// Calculer les utilisateurs uniques
const uniqueUsers = computed(() =>
  [...new Set(reactions.value.map(r => r.user?.name || "Utilisateur inconnu"))]
)

// Filtrage des réactions
const filteredReactions = computed(() =>
  reactions.value.filter(r => {
    const matchUser = !selectedUser.value || r.user?.name === selectedUser.value
    const matchReaction = !selectedReactionType.value || r.reaction === selectedReactionType.value
    const matchSearch = !searchText.value || r.message.toLowerCase().includes(searchText.value.toLowerCase())
    return matchUser && matchReaction && matchSearch
  })
)

// Pagination
const paginatedReactions = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return filteredReactions.value.slice(start, start + Number(itemsPerPage.value))
})

const totalPages = computed(() => Math.ceil(filteredReactions.value.length / itemsPerPage.value))

// Affichage intelligent des pages
const displayedPages = computed(() => {
  const totalPagesCount = totalPages.value
  const currentPageVal = currentPage.value
  
  if (totalPagesCount <= 7) {
    return Array.from({ length: totalPagesCount }, (_, i) => i + 1)
  }
  
  if (currentPageVal <= 3) {
    return [1, 2, 3, 4, '...', totalPagesCount]
  }
  
  if (currentPageVal >= totalPagesCount - 2) {
    return [1, '...', totalPagesCount - 3, totalPagesCount - 2, totalPagesCount - 1, totalPagesCount]
  }
  
  return [
    1, 
    '...', 
    currentPageVal - 1,
    currentPageVal,
    currentPageVal + 1,
    '...', 
    totalPagesCount
  ]
})

// Statistiques
const totalLikes = computed(() => reactions.value.filter(r => r.reaction === '👍').length)
const totalDislikes = computed(() => reactions.value.filter(r => r.reaction === '👎').length)

// Actions
const resetFilters = () => {
  selectedUser.value = ""
  selectedReactionType.value = ""
  searchText.value = ""
  currentPage.value = 1
}

const openModal = (reaction) => {
  selectedReaction.value = reaction
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const openDislikeModal = (reaction) => {
  selectedDislike.value = reaction
  showDislikeModal.value = true
}

const closeDislikeModal = () => {
  showDislikeModal.value = false
}
</script>

<style scoped>
.reactions-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  background-color: aliceblue;
  border-radius: 1.5rem;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
  font-family: 'Inter', system-ui, sans-serif;
}

.dashboard-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 1.5rem;
  position: relative;
  display: inline-block;
}

.dashboard-title::after {
  content: '';
  position: absolute;
  bottom: -0.5rem;
  left: 0;
  width: 3rem;
  height: 0.25rem;
  background: #3b82f6;
  border-radius: 0.125rem;
}

/* Stats Cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  display: flex;
  align-items: center;
  background: white;
  padding: 1.5rem;
  border-radius: 1rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
}

.stat-card.positive {
  border-left: 5px solid #34d399;
}

.stat-card.negative {
  border-left: 5px solid #fb7185;
}

.stat-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 3rem;
  height: 3rem;
  border-radius: 0.75rem;
  margin-right: 1.25rem;
  font-size: 1.5rem;
  background: #f9f9f9;
}

.positive .stat-icon {
  background: #ecfdf5;
  color: #10b981;
}

.negative .stat-icon {
  background: #fff1f2;
  color: #e11d48;
}

.stat-content {
  flex: 1;
}

.stat-label {
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.stat-value {
  font-size: 1.75rem;
  font-weight: 700;
  color: #0f172a;
}

/* Filters */
.filters-container {
  margin-bottom: 2rem;
}

.search-wrapper {
  position: relative;
  margin-bottom: 1rem;
}

.search-input {
  width: 100%;
  padding: 0.875rem 1rem 0.875rem 2.75rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  background: white;
  font-size: 0.95rem;
  color: #1e293b;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.search-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
  outline: none;
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 1rem;
}

.filters-group {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.filter-select {
  padding: 0.75rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  background: white;
  font-size: 0.95rem;
  color: #1e293b;
  transition: border-color 0.2s, box-shadow 0.2s;
  min-width: 10rem;
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1rem;
  padding-right: 2.5rem;
}

.filter-select:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
  outline: none;
}

.reset-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  border: none;
  background: #f1f5f9;
  color: #334155;
  border-radius: 0.75rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.reset-button:hover {
  background: #e2e8f0;
}

.reset-icon {
  font-size: 1rem;
}

/* Data Table */
.data-table {
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  margin-bottom: 1.5rem;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 4fr 1fr 2fr;
  padding: 1rem 1.5rem;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
  color: #475569;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.data-row {
  display: grid;
  grid-template-columns: 2fr 4fr 1fr 2fr;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  transition: background-color 0.2s;
}

.data-row:last-child {
  border-bottom: none;
}

.data-row:hover {
  background-color: #f8fafc;
}

.user-cell {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.avatar {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 1rem;
}

.username {
  font-weight: 500;
  color: #0f172a;
}

.message-cell {
  color: #334155;
  cursor: pointer;
  transition: color 0.2s;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.message-cell:hover {
  color: #3b82f6;
  text-decoration: underline;
}

.reaction-cell {
  display: flex;
  align-items: center;
}

.reaction-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.375rem 0.75rem;
  border-radius: 9999px;
  font-weight: 600;
  font-size: 0.85rem;
}

.like-badge {
  background: #ecfdf5;
  color: #10b981;
}

.dislike-badge {
  background: #fff1f2;
  color: #e11d48;
  cursor: pointer;
}

.date-cell {
  color: #64748b;
  font-size: 0.85rem;
}

/* Empty State */
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 1rem;
  background: white;
  border-radius: 1rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.empty-icon {
  font-size: 2rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state p {
  color: #64748b;
  margin-bottom: 1.5rem;
}

.empty-button {
  padding: 0.75rem 1.5rem;
  background: #3b82f6;
  border: none;
  border-radius: 0.75rem;
  color: white;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.empty-button:hover {
  background: #2563eb;
}

/* Pagination */
.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 2rem;
}

.page-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.5rem;
  height: 2.5rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  background: white;
  color: #475569;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s, border-color 0.2s;
}

.page-button:hover:not(:disabled) {
  border-color: #3b82f6;
  color: #3b82f6;
}

.page-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-arrow {
  font-size: 1.25rem;
}

.page-numbers {
  display: flex;
  gap: 0.5rem;
}

.page-number {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.5rem;
  height: 2.5rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  background: white;
  color: #475569;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s, border-color 0.2s;
}

.page-number:hover:not(.active) {
  border-color: #3b82f6;
  color: #3b82f6;
}

.page-number.active {
  background: #3b82f6;
  border-color: #3b82f6;
  color: white;
}

/* Modals */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 23, 42, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
  backdrop-filter: blur(4px);
}

.modal-content {
  width: 100%;
  max-width: 600px;
  background: white;
  border-radius: 1rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  overflow: hidden;
  animation: slideIn 0.3s ease;
  max-height: 90vh; /* empêche de dépasser l’écran */
  display: flex;
  flex-direction: column;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.dislike-header {
  background: #fff1f2;
}

.modal-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0;
}

.dislike-header h3 {
  color: #e11d48;
}

.modal-close {
  background: transparent;
  border: none;
  font-size: 1.5rem;
  color: #64748b;
  cursor: pointer;
  transition: color 0.2s;
}

.modal-close:hover {
  color: #0f172a;
}

.modal-body {
  padding: 1.5rem;
  overflow-y: auto;
  max-height: 60vh; /* hauteur visible avec scroll si besoin */
}

.detail-row {
  margin-bottom: 1.25rem;
}

.detail-row:last-child {
  margin-bottom: 0;
}

.detail-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #64748b;
  margin-bottom: 0.5rem;
}

.detail-value {
  color: #0f172a;
  font-size: 1rem;
}

.message-detail {
  padding: 1rem;
  background: #f8fafc;
  border-radius: 0.75rem;
  white-space: pre-wrap;
}

.user-message {
  padding: 1rem;
  background: #f8fafc;
  border-radius: 0.75rem;
  font-style: italic;
  color: #475569;
  white-space: pre-wrap;
}

.bot-response {
  padding: 1rem;
  background: #eff6ff;
  border-radius: 0.75rem;
  white-space: pre-wrap;
}

.modal-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
}

.modal-button {
  padding: 0.75rem 1.5rem;
  background: #3b82f6;
  border: none;
  border-radius: 0.75rem;
  color: white;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.modal-button:hover {
  background: #2563eb;
}

/* Responsive */
@media (max-width: 768px) {
  .table-header, .data-row {
    grid-template-columns: 2fr 3fr 1fr 1.5fr;
    font-size: 0.85rem;
  }
  
  .avatar {
    width: 2rem;
    height: 2rem;
    font-size: 0.875rem;
  }
  
  .reactions-container {
    padding: 1.5rem;
  }
  
  .filters-group {
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .filter-select {
    width: 100%;
  }
}

@media (max-width: 576px) {
  .table-header {
    display: none;
  }
  
  .data-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
    padding: 1.25rem;
  }
  
  .data-row:not(:last-child) {
    border-bottom: 1px solid #e2e8f0;
  }
  
  .user-cell, .message-cell, .reaction-cell, .date-cell {
    padding: 0;
  }
  
  .message-cell:before {
    content: 'Message:';
    display: block;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.25rem;
  }
  
  .reaction-cell:before {
    content: 'Réaction:';
    display: block;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.25rem;
  }
  
  .date-cell:before {
    content: 'Date:';
    display: block;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.25rem;
  }
}




</style>