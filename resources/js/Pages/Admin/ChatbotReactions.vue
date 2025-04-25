<template>
  <Main>
    <div class="reactions-wrapper">
      <h2 class="section-title">Statistiques des Réactions</h2>
  <!-- Statistiques -->
  <div class="stats-container">
        <div class="stat-box like">
          <i class="icon">👍</i>
          <div>
            <div class="label">Likes</div>
            <div class="value">{{ totalLikes }}</div>
          </div>
        </div>
        <div class="stat-box dislike">
          <i class="icon">👎</i>
          <div>
            <div class="label">Dislikes</div>
            <div class="value">{{ totalDislikes }}</div>
          </div>
        </div>
      </div>
    <!-- Filtres -->
    <div class="filters-bar">
        <input v-model="searchText" placeholder="Rechercher un message..." class="input-text" />

        <select v-model="selectedUser" class="input-select">
          <option value="">Tous les utilisateurs</option>
          <option v-for="u in uniqueUsers" :key="u">{{ u }}</option>
        </select>

        <select v-model="selectedReactionType" class="input-select">
          <option value="">Toutes les réactions</option>
          <option value="👍">👍 Likes</option>
          <option value="👎">👎 Dislikes</option>
        </select>

        <select v-model="itemsPerPage" class="input-select">
          <option value="5">5 par page</option>
          <option value="10">10 par page</option>
        </select>

        <button class="btn-reset" @click="resetFilters">Réinitialiser</button>
      </div>

      <!-- En-tête du tableau -->
      <div class="table-header">
        <span style="margin-left: 22%">Utilisateur</span>
        <span style="margin-left: 26%">Message</span>
        <span style="margin-left: 20%">Réaction</span>
        <span style="margin-left: 31%">Date</span>
      </div>

      <!-- Données -->
      <div v-for="(r, i) in paginatedReactions" :key="i" class="data-row">
        <div class="user-info" style="margin-left: 1%">
          <div class="user-avatar">{{ getInitial(r.user?.name || "U") }}</div>
          <span class="user-name">{{ r.user?.name || "Utilisateur inconnu" }}</span>
        </div>

        <span class="message clickable" :title="r.message" @click="openModal(r)" >
          {{ truncate(r.message, 60) }}
        </span>

        <div
  :class="['badge', r.reaction === '👍' ? 'like' : 'dislike']"
 
  @click="r.reaction === '👎' && r.user ? openDislikeModal(r) : null"
  >
  {{ r.reaction }}
</div>

        <span class="date" style="margin-left: 30%">{{ formatDate(r.created_at) }}</span>
      </div>

      <!-- Pagination -->
      <div class="pagination-bar" v-if="totalPages > 1">
  <button
    class="pagination-arrow"
    :disabled="currentPage === 1"
    @click="currentPage--"
  >
    ‹
  </button>

  <span
    v-for="page in totalPages"
    :key="page"
    :class="['pagination-number', { active: currentPage === page }]"
    @click="currentPage = page"
  >
    {{ page }}
  </span>

  <button
    class="pagination-arrow"
    :disabled="currentPage === totalPages"
    @click="currentPage++"
  >
    ›
  </button>
</div>

      <!-- État vide -->
      <div v-if="filteredReactions.length === 0" class="empty-state">
        Aucune réaction trouvée pour ces filtres.
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-box">
        <h3>Détail de la réaction</h3>
        <p><strong>Utilisateur :</strong> {{ selectedReaction?.user?.name || 'Utilisateur inconnu' }}</p>
        <p><strong>Message :</strong> {{ selectedReaction?.message }}</p>
        <p><strong>Réaction :</strong> {{ selectedReaction?.reaction }}</p>
        <p><strong>Date :</strong> {{ formatDate(selectedReaction?.created_at) }}</p>
        <button class="close-btn" @click="closeModal">Fermer</button>
      </div>
    </div>
<!-- Modal Dislike -->
<div v-if="showDislikeModal" class="modal-overlay" @click.self="closeDislikeModal">
  <div class="modal-box">
    <h3>Message signalé 👎</h3>

    <p><strong>Utilisateur :</strong> {{ selectedDislike?.user?.name || 'Utilisateur inconnu' }}</p>

    <p><strong>Message de l'utilisateur :</strong><br />
      <em>{{ selectedDislike?.originalMessage || 'Message original non disponible.' }}</em>
    </p>

    <p><strong>Réponse du bot :</strong><br />
      {{ selectedDislike?.message }}
    </p>

    <button class="close-btn" @click="closeDislikeModal">Fermer</button>
  </div>
</div>
  </Main>
</template>


<script setup>

import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Main from '@/Layouts/Main.vue'
const selectedReactionType = ref("")

const showModal = ref(false)
const selectedReaction = ref(null)
const showDislikeModal = ref(false)
const selectedDislike = ref(null)
const openDislikeModal = (reaction) => {
  selectedDislike.value = reaction
  showDislikeModal.value = true
}
const reactions = computed(() => {
  const data = props.reactions || []
  return data.map(r => ({
    ...r,
    originalMessage: r.originalMessage || "Message utilisateur simulé"
  }))
})
const closeDislikeModal = () => showDislikeModal.value = false

const openModal = (reaction) => {
  selectedReaction.value = reaction
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const props = usePage().props
//const reactions = computed(() => props.reactions || [])

const selectedUser = ref("")
const searchText = ref("")
const currentPage = ref(1)
const itemsPerPage = ref(5)

const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  return date.toLocaleDateString("fr-FR")
}

const getInitial = (name) => name?.charAt(0).toUpperCase() || 'U'
const truncate = (text, len) => (text.length > len ? text.slice(0, len) + "..." : text)

const uniqueUsers = computed(() =>
  [...new Set(reactions.value.map(r => r.user?.name || "Utilisateur inconnu"))]
)

const resetFilters = () => {
  selectedUser.value = ""
  selectedReactionType.value = ""
  searchText.value = ""
  currentPage.value = 1
}
const paginatedReactions = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return filteredReactions.value.slice(start, start + Number(itemsPerPage.value))
})
const totalPages = computed(() => Math.ceil(filteredReactions.value.length / itemsPerPage.value))
const totalLikes = computed(() => reactions.value.filter(r => r.reaction === '👍').length)
const totalDislikes = computed(() => reactions.value.filter(r => r.reaction === '👎').length)

const filteredReactions = computed(() =>
  reactions.value.filter(r => {
    const matchUser = !selectedUser.value || r.user?.name === selectedUser.value
    const matchReaction = !selectedReactionType.value || r.reaction === selectedReactionType.value
    const matchSearch = !searchText.value || r.message.toLowerCase().includes(searchText.value.toLowerCase())
    return matchUser && matchReaction && matchSearch
  })
)
</script>

<style scoped>
.stats-container {
  display: flex;
  gap: 24px;
  margin-bottom: 25px;
}
.stat-box {
  display: flex;
  align-items: center;
  background: white;
  padding: 16px 24px;
  border-radius: 14px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  gap: 16px;
  min-width: 160px;
}
.stat-box .icon {
  color: white;
  background: #22c55e;
  border-radius: 30%;
  padding: 10px;
}
.stat-box.dislike .icon {
  background: #ef4444;
}
.label {
  font-weight: 600;
  color: #1e293b;
}
.value {
  font-size: 20px;
  font-weight: bold;
  color: #1e293b;
}
.pagination-bar {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
  gap: 8px;
}

.pagination-arrow,
.pagination-number {
  border: 1.5px solid #38bdf8;
  color: #38bdf8;
  border-radius: 10px;
  padding: 6px 12px;
  background: white;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(56, 189, 248, 0.2);
  transition: 0.2s;
}

.pagination-number.active {
  background-color: #38bdf8;
  color: white;
}

.pagination-arrow:disabled {
  opacity: 0.3;
  cursor: not-allowed;
  box-shadow: none;
}


.clickable {
  cursor: pointer;
  text-decoration: underline;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 23, 42, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.modal-box {
  background: white;
  border-radius: 20px;
  padding: 28px 30px;
  width: 100%;
  max-width: 550px;
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
  font-family: 'Inter', sans-serif;
  color: #1e293b;
  animation: fadeIn 0.3s ease-in-out;
  line-height: 1.6;
}

.modal-box h3 {
  font-size: 20px;
  font-weight: 700;
  color: #dc2626; /* rouge foncé pour les messages signalés */
  margin-bottom: 20px;
}

.modal-box p {
  margin-bottom: 12px;
  font-size: 15px;
}

.modal-box strong {
  color: #0f172a;
  font-weight: 600;
}

.modal-box em {
  color: #475569;
  font-style: italic;
  background-color: #f1f5f9;
  padding: 6px 10px;
  border-radius: 8px;
  display: inline-block;
  margin-top: 6px;
}

.close-btn {
  background-color: #2563eb;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 12px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  margin-top: 20px;
  transition: background-color 0.2s;
}

.close-btn:hover {
  background-color: #1d4ed8;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.reactions-wrapper {
  padding: 30px;
  background: #f0f7ff;
  border-radius: 20px;
  margin: auto;
  font-family: 'Inter', sans-serif;
  color: #1e293b;
}

.section-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 25px;
  color: #1d4ed8;
}

.filters-bar {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  margin-bottom: 30px;
  background: transparent;
}

.input-text,
.input-select {
  background-color: white;
  border: none;
  padding: 12px 18px;
  border-radius: 16px;
  min-width: 180px;
  font-size: 15px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  color: gray;
  outline: none;
  transition: 0.2s;
}

.input-text::placeholder {
  color: #94a3b8;
}

.input-text:focus,
.input-select:focus {
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}


.btn-reset {
  background: white;
  color: #dc2626;
  border: 2px solid #dc2626;
  padding: 10px 18px;
  border-radius: 14px;
  font-weight: 600;
  font-size: 15px;
  cursor: pointer;
  transition: 0.2s;
}
.btn-reset:hover {
  background: #dc2626;
  color: white;
}


.table-header {
  background: #e0edfa;
  border-radius: 16px;
  padding: 14px 24px;
  font-weight: 700;
  font-size: 14px;
  color: #1d4ed8;
  letter-spacing: 0.5px;
  display: grid;
  grid-template-columns: 2fr 5fr 1.5fr 2fr;
  margin-bottom: 10px;
  text-transform: uppercase;
}

.data-row {
  background: white;
  border-radius: 16px;
  padding: 14px 24px;
  display: grid;
  grid-template-columns: 2fr 5fr 1.5fr 2fr;
  align-items: center;
  margin-bottom: 12px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
  transition: 0.2s ease-in-out;
}

.data-row:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  background: #2563eb;
  color: white;
  border-radius: 50%;
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 18px;
  box-shadow: 0 2px 8px rgba(37, 99, 235, 0.10);
}

.user-name {
  font-weight: 600;
  color: #1e293b;
}

.message {
  color: #475569;
  font-size: 15px;
}

.badge {
  border-radius: 999px;
  font-size: 14px;
  padding: 6px 14px;
  font-weight: 600;
  background: #f1f5f9;
  color: #1e293b;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.like {
  background-color: #dcfce7;
  color: #16a34a;
}

.dislike {
  background-color: #fee2e2;
  color: #dc2626;
}

.date {
  font-size: 13px;
  color: #64748b;
  font-weight: 500;
}

.empty-state {
  text-align: center;
  color: #94a3b8;
  font-size: 15px;
  margin-top: 20px;
}
</style>
