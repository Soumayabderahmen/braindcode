<script setup>
import { ref, computed, onMounted } from 'vue'

const search = ref('')
const category = ref('')
const isAnimated = ref(false)

const resources = ref([
  {
    type: 'document',
    title: 'Guide du Pitch parfait',
    content: 'Apprenez à construire un pitch deck qui captive les investisseurs.',
    link: '#',
    icon: 'bi-file-earmark-text',
    color: '#4361ee'
  },
  {
    type: 'document',
    title: 'Template Business Plan',
    content: 'Structurez votre plan d\'affaires avec notre modèle complet.',
    link: '#',
    icon: 'bi-file-earmark-text',
    color: '#4361ee'
  },
  {
    type: 'video',
    title: 'Comment lever des fonds en 2023',
    content: 'Stratégies efficaces pour sécuriser votre levée de fonds.',
    link: '#',
    icon: 'bi-play-circle',
    color: '#ef476f'
  },
  {
    type: 'article',
    title: 'Les tendances tech de 2024',
    content: 'Découvrez les innovations technologiques à venir.',
    link: '#',
    icon: 'bi-newspaper',
    color: '#06d6a0'
  }
  // 🔁 Ajoute tous les éléments restants ici
])

const filtered = computed(() =>
  resources.value.filter((res) => {
    const matchesSearch = search.value === '' || res.title.toLowerCase().includes(search.value.toLowerCase()) || 
                          res.content.toLowerCase().includes(search.value.toLowerCase())
    const matchesCategory = category.value === '' || res.type === category.value
    return matchesSearch && matchesCategory
  })
)

onMounted(() => {
  setTimeout(() => {
    isAnimated.value = true
  }, 100)
})

const getButtonClass = (cat) => {
  if (cat === category.value) return 'btn-primary active'
  return 'btn-outline-primary'
}

const clearSearch = () => {
  search.value = ''
}
</script>

<template>
  <section class="resources py-5" 
    style="background: linear-gradient(rgba(248, 249, 250, 0.9), rgba(248, 249, 250, 0.9)), url('/asset/img/fond2.png'); background-size: cover;">
    <div class="container">
      <div class="text-center mb-5" :class="{ 'fade-in': isAnimated }">
        <h2 class="display-5 fw-bold text-primary mb-3">Ressources</h2>
        <p class="lead text-muted col-lg-8 mx-auto">
          Espace dédié aux documents, vidéos et articles pour vous accompagner dans votre parcours entrepreneurial.
        </p>
        <div class="separator my-4"></div>
      </div>

      <div class="search-filter-container mb-5" :class="{ 'slide-in': isAnimated }">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="search-box position-relative">
              <input 
                v-model="search" 
                type="text" 
                class="form-control form-control-lg rounded-pill px-4 py-3 shadow-sm" 
                placeholder="Rechercher une ressource..."
              >
              <button v-if="search" @click="clearSearch" class="btn-clear position-absolute">
                <i class="bi bi-x-circle"></i>
              </button>
              <i class="bi bi-search position-absolute search-icon"></i>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="category-filters d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
              <button @click="category = ''" :class="getButtonClass('')" class="btn rounded-pill px-4">
                <i class="bi bi-grid me-2"></i>Tous
              </button>
              <button @click="category = 'document'" :class="getButtonClass('document')" class="btn rounded-pill px-4">
                <i class="bi bi-file-earmark-text me-2"></i>Documents
              </button>
              <button @click="category = 'video'" :class="getButtonClass('video')" class="btn rounded-pill px-4">
                <i class="bi bi-play-circle me-2"></i>Vidéos
              </button>
              <button @click="category = 'article'" :class="getButtonClass('article')" class="btn rounded-pill px-4">
                <i class="bi bi-newspaper me-2"></i>Articles
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="filtered.length === 0" class="text-center py-5">
        <div class="empty-state">
          <i class="bi bi-search display-1 text-muted"></i>
          <h4 class="mt-3">Aucun résultat trouvé</h4>
          <p>Essayez de modifier vos critères de recherche</p>
          <button @click="search = ''; category = ''" class="btn btn-primary rounded-pill px-4 mt-2">
            Réinitialiser la recherche
          </button>
        </div>
      </div>

      <div class="row g-4">
        <div v-for="(item, i) in filtered" :key="i" class="col-md-6 col-lg-4" :class="{ 'fade-in': isAnimated }" :style="{ animationDelay: `${i * 0.1}s` }">
          <div class="card h-100 shadow border-0 rounded-4 resource-card hover-scale">
            <div class="card-body p-4">
              <div class="resource-icon mb-3" :style="{ backgroundColor: item.color + '15' }">
                <i class="bi" :class="item.icon" :style="{ color: item.color }"></i>
              </div>
              <div class="resource-badge" :style="{ backgroundColor: item.color }">
                {{ item.type }}
              </div>
              <h5 class="card-title fw-bold">{{ item.title }}</h5>
              <p class="card-text text-muted">{{ item.content }}</p>
            </div>
            <div class="card-footer bg-transparent border-0 p-4 pt-0">
              <a :href="item.link" class="btn btn-primary btn-sm rounded-pill px-4 py-2 d-flex align-items-center justify-content-center gap-2">
                <i class="bi" :class="item.type === 'video' ? 'bi-play-fill' : item.type === 'article' ? 'bi-book' : 'bi-download'"></i>
                {{ item.type === 'video' ? 'Visionner' : item.type === 'article' ? 'Lire l\'article' : 'Télécharger' }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.resources {
  position: relative;
  min-height: 100vh;
  transition: all 0.3s ease;
}

.separator {
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #4361ee, #06d6a0);
  margin: 0 auto;
  border-radius: 2px;
}

.search-box {
  position: relative;
}

.search-icon {
  top: 50%;
  right: 20px;
  transform: translateY(-50%);
  color: #4361ee;
}

.btn-clear {
  background: none;
  border: none;
  top: 50%;
  right: 20px;
  transform: translateY(-50%);
  color: #6c757d;
  cursor: pointer;
}

.resource-card {
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.resource-badge {
  position: absolute;
  top: 20px;
  right: 20px;
  padding: 4px 12px;
  border-radius: 20px;
  color: white;
  font-size: 0.8rem;
  text-transform: uppercase;
  font-weight: bold;
}

.resource-icon {
  width: 60px;
  height: 60px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.resource-icon i {
  font-size: 1.8rem;
}

.hover-scale:hover {
  transform: translateY(-5px);
}

.empty-state {
  padding: 40px;
  color: #6c757d;
}

/* Animations */
.fade-in {
  animation: fadeIn 0.8s ease forwards;
  opacity: 0;
}

.slide-in {
  animation: slideIn 0.8s ease forwards;
  opacity: 0;
  transform: translateY(20px);
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
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

@media (max-width: 768px) {
  .category-filters {
    justify-content: center;
    margin-top: 1rem;
  }
}
</style>