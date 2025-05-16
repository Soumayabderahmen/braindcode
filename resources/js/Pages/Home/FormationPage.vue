<template>
  <section>
    <!-- Header - Design plus professionnel avec dégradé et typographie améliorée -->
    <section class="row align-items-center mb-5 header-pro">
      <div class="container py-5">
        <div class="row">
          <div class="col-md-6">
            <h2 class="formation-title fw-bold">
              Explorez notre univers de<br><span class="text-primary">formations</span>
            </h2>
            <p class="formation-subtitle text-secondary fs-5 mt-3">
              Braindcode Startup Studio est un espace conçu pour propulser startups et professionnels à travers des formations ciblées, dynamiques et enrichies par l'intelligence artificielle.
            </p>
            <button class="btn btn-primary rounded-pill px-4 py-2 mt-3 fw-semibold">
              Découvrir nos programmes
            </button>
          </div>
          <div class="col-md-6 text-center">
            <img src="/asset/img/formation1.png" class="img-fluid rounded shadow-lg" alt="Illustration formation" />
          </div>
        </div>
      </div>
    </section>

    <!-- Approche - Design épuré avec icônes et cartes structurées -->
    <section class="approach-section py-5 bg-light">
      <div class="container">
        <div class="text-center mb-5">
          <h6 class="text-primary text-uppercase fw-bold small">Méthodologie</h6>
          <h2 class="fw-bold mb-3">Notre approche unique</h2>
          <p class="text-muted mx-auto" style="max-width: 700px;">
            Chez BrainCode, nous croyons en une pédagogie immersive et évolutive qui s'adapte aux besoins des professionnels et startups d'aujourd'hui.
          </p>
        </div>
        
        <div class="row g-4">
          <div class="col-md-3" v-for="(item, i) in approche" :key="i">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body p-4 text-center">
                <div class="icon-circle mb-3 mx-auto d-flex align-items-center justify-content-center">
                  <i :class="item.icon"></i>
                </div>
                <h5 class="fw-bold">{{ item.title }}</h5>
                <p class="text-muted small mb-0">{{ item.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Formations - Design professionnel avec filtres améliorés -->
    <section class="formation-section py-5">
      <div class="container">
        <div class="text-center mb-5">
          <h6 class="text-primary text-uppercase fw-bold small">Catalogue</h6>
          <h2 class="fw-bold mb-3">Nos formations</h2>
          <p class="text-muted mx-auto" style="max-width: 700px;">
            Nos formations sont conçues pour s'adapter aux besoins concrets des startups et professionnels en quête d'excellence.
          </p>
        </div>

        <!-- Filtre - Design plus élégant -->
        <div class="row justify-content-center mb-5">
          <div class="col-md-8">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="row g-3">
                  <div class="col-md-7">
                    <div class="input-group">
                      <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                      </span>
                      <input v-model="search" type="text" class="form-control border-start-0" placeholder="Rechercher une formation..." />
                    </div>
                  </div>
                  <div class="col-md-5">
                    <select v-model="selectedCategory" class="form-select">
                      <option value="">Toutes les catégories</option>
                      <option v-for="cat in uniqueCategories" :key="cat">{{ cat }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Grille - Design plus épuré et professionnel -->
        <div class="row g-4">
          <div class="col-md-4" v-for="formation in filteredFormations" :key="formation.title">
            <div class="card h-100 border-0 shadow-sm formation-card">
              <div class="position-relative">
                <img :src="formation.image" :alt="formation.title" class="card-img-top">
                <span class="badge bg-primary position-absolute top-0 end-0 m-3">{{ formation.category }}</span>
              </div>
              <div class="card-body p-4 d-flex flex-column">
                <h5 class="card-title fw-bold">{{ formation.title }}</h5>
                <p class="card-text text-muted">{{ formation.description }}</p>
                <div class="mt-auto pt-3 d-flex justify-content-between align-items-center">
                  <div class="small">
                    <i class="bi bi-clock me-1"></i> 12 heures
                  </div>
                  <a :href="formation.link" class="btn btn-outline-primary rounded-pill">Voir détails</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination - Style amélioré -->
        <div class="mt-5 text-center">
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </section>
    
    <!-- CTA Section - Nouvelle section -->
  
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const search = ref('')
const selectedCategory = ref('')

const formations = ref([
  {
    title: 'Stratégie de levée de fonds',
    description: 'Apprenez à construire une campagne de financement efficace et à convaincre les investisseurs.',
    image: '/asset/img/fo1.png',
    link: '/formation2',
    category: 'Financement'
  },
  {
    title: 'Marketing digital avancé',
    description: 'Maîtrisez les outils pour booster votre visibilité et votre acquisition client.',
    image: '/asset/img/fo2.png',
    link: '/formation2',
    category: 'Marketing'
  },
  {
    title: 'Leadership entrepreneurial',
    description: 'Développez votre posture de leader dans l\'écosystème startup et managez efficacement.',
    image: '/asset/img/fo3.png',
    link: '/formation2',
    category: 'Développement personnel'
  },
  {
    title: 'Design Thinking & UX',
    description: 'Construisez des produits centrés utilisateur avec une méthodologie éprouvée.',
    image: '/asset/img/fo4.png',
    link: '/formation2',
    category: 'UX/UI'
  },
  {
    title: 'Pitch Deck percutant',
    description: 'Structurez une présentation claire et convaincante pour séduire vos partenaires.',
    image: '/asset/img/fo5.png',
    link: '/formation2',
    category: 'Pitch'
  },
  {
    title: 'Stratégie de croissance',
    description: 'Planifiez vos étapes de scaling de manière stratégique et maîtrisée.',
    image: '/asset/img/fo6.png',
    link: '/formation2',
    category: 'Croissance'
  },
  {
    title: 'Levée de fonds internationale',
    description: 'Ciblez les investisseurs internationaux avec une approche adaptée et impactante.',
    image: '/asset/img/f7.png',
    link: '/formation2',
    category: 'Financement'
  },
  {
    title: 'Growth Hacking',
    description: 'Boostez votre traction avec des techniques de growth hacking innovantes.',
    image: '/asset/img/f8.png',
    link: '/formation2',
    category: 'Marketing'
  },
  {
    title: 'Finance pour entrepreneurs',
    description: 'Comprendre la gestion financière en startup et sécuriser votre modèle économique.',
    image: '/asset/img/f9.png',
    link: '/formation2',
    category: 'Finance'
  }
])

const approche = ref([
  {
    title: 'Parcours modulaires sur mesure',
    desc: 'Choisissez les modules qui correspondent précisément à vos besoins et objectifs professionnels.',
    icon: 'bi bi-grid-3x3-gap-fill'
  },
  {
    title: 'Études de cas et projets réels',
    desc: 'Apprenez en résolvant des situations concrètes inspirées de cas d\'entreprises innovantes.',
    icon: 'bi bi-clipboard-data'
  },
  {
    title: 'Communautés et forums actifs',
    desc: 'Échangez avec d\'autres participants et progressez ensemble grâce à l\'intelligence collective.',
    icon: 'bi bi-people-fill'
  },
  {
    title: 'Suivi clair et feedback continu',
    desc: 'Visualisez votre progression et recevez des retours personnalisés de nos experts.',
    icon: 'bi bi-graph-up-arrow'
  }
])

const uniqueCategories = computed(() =>
  [...new Set(formations.value.map(f => f.category))].sort()
)

const filteredFormations = computed(() =>
  formations.value.filter(f =>
    (!search.value || f.title.toLowerCase().includes(search.value.toLowerCase())) &&
    (!selectedCategory.value || f.category === selectedCategory.value)
  )
)
</script>

<style scoped>
/* Styles professionnels */
.header-pro {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-bottom: 1px solid rgba(0,0,0,0.05);
}

.formation-title {
  font-size: 2.5rem;
  letter-spacing: -0.5px;
  line-height: 1.2;
}

.icon-circle {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  background-color: rgba(13, 110, 253, 0.1);
  color: #0d6efd;
  font-size: 1.5rem;
}

.formation-card {
  transition: transform 0.3s ease;
}

.formation-card:hover {
  transform: translateY(-5px);
}

.card {
  border-radius: 10px;
  overflow: hidden;
}

.card img {
  height: 200px;
  object-fit: cover;
}

.page-link {
  border-radius: 50%;
  margin: 0 5px;
  color: #0d6efd;
}

.page-item.active .page-link {
  background-color: #0d6efd;
  border-color: #0d6efd;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .formation-title {
    font-size: 2rem;
  }
  
  .card img {
    height: 180px;
  }
}
</style>