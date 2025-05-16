<template>
  <section class="py-12 bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="container mx-auto px-4">
      <!-- Header avec design moderne -->
      <div class="text-center mb-12">
        <h2 class="font-bold text-3xl mb-3 text-gray-800">Startups <span class="text-blue-600">diplômées</span></h2>
        <div class="w-20 h-1 bg-blue-600 mx-auto mb-6"></div>
        <p class="text-gray-600 max-w-2xl mx-auto">
          Ces startups ont été incubées dans notre programme et ont traversé un parcours de transformation pour devenir des entreprises innovantes à fort potentiel.
        </p>
      </div>

      <!-- Filtres avec design amélioré -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-10 max-w-3xl mx-auto">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="flex-1">
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </span>
              <input 
                type="text" 
                v-model="search" 
                class="form-input w-full py-3 pl-10 pr-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" 
                placeholder="Rechercher une startup"
              >
            </div>
          </div>
          <div class="md:w-1/3">
            <div class="relative">
              <select 
                v-model="category" 
                class="form-select w-full py-3 pl-4 pr-10 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent appearance-none"
              >
                <option value="">Toutes les catégories</option>
                <option value="Agritech">Agritech</option>
                <option value="Santé">Santé</option>
                <option value="Éducation">Éducation</option>
                <option value="Mobilité">Mobilité</option>
                <option value="Environnement">Environnement</option>
                <option value="Écologie">Écologie</option>
                <option value="Domotique">Domotique</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Message quand aucun résultat -->
      <div v-if="filteredStartups.length === 0" class="text-center py-10">
        <p class="text-gray-600">Aucune startup ne correspond à votre recherche.</p>
      </div>

      <!-- Cartes avec design professionnel -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="(startup, index) in filteredStartups" :key="index" class="transition-transform duration-300 hover:-translate-y-2">
          <div class="bg-white rounded-lg overflow-hidden shadow-lg h-full flex flex-col">
           <div class="h-48 w-full overflow-hidden relative">
  <img 
    :src="startup.image" 
    :alt="startup.name" 
    class="h-full w-full object-cover"
  >
  <div class="bg-black bg-opacity-10 w-full h-full absolute top-0 left-0"></div>
  <span class="absolute top-3 right-3 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow">
    {{ startup.category }}
  </span>
</div>

            <div class="p-6 flex-1 flex flex-col">
              <h3 class="font-bold text-xl mb-2 text-gray-800">{{ startup.name }}</h3>
              <p class="text-gray-600 mb-4 flex-1">{{ startup.description }}</p>
              <div class="border-t border-gray-200 pt-4 mt-auto">
                <div class="flex items-center text-gray-600 text-sm mb-2">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                  </svg>
                  <span>{{ startup.phone }}</span>
                </div>
                <div class="flex items-center text-gray-600 text-sm mb-4">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <a :href="startup.website" class="text-blue-600 hover:underline" target="_blank">{{ startup.website }}</a>
                </div>
                <a 
                  :href="startup.link" 
                  class="inline-block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300 text-center"
                  target="_blank"
                >
                  En savoir plus
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination améliorée -->
      <div class="mt-12 flex justify-center">
        <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
          <a href="#" class="relative inline-flex items-center rounded-l-md px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Précédent</span>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </a>
          <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 hover:bg-blue-700 focus:z-20 focus:outline-offset-0">
            1
          </a>
          <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            2
          </a>
          <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            3
          </a>
          <a href="#" class="relative inline-flex items-center rounded-r-md px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Suivant</span>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </nav>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';

const search = ref('');
const category = ref('');

// Données des startups
const startups = ref([
  {
    name: "EcoGrow",
    description: "Optimise la production agricole tout en respectant l'environnement.",
    image: "/asset/img/egocrow.png",
    phone: "+216 23 123 578",
    website: "www.ecogrow.com",
    link: "/startinc2",
    category: "Agritech"
  },
  {
    name: "HealthSync",
    description: "Connecte patients et médecins via une app mobile.",
    image: "/asset/img/health.png",
    phone: "+216 23 123 543",
    website: "www.healthsync.com",
    link: "/startinc2",
    category: "Santé"
  },
  {
    name: "EduFlex",
    description: "Formations interactives pour étudiants et professionnels.",
    image: "/asset/img/eduflex.png",
    phone: "+216 23 222 222",
    website: "www.eduflex.org",
    link: "/startinc2",
    category: "Éducation"
  },
  {
    name: "Mobylink",
    description: "Solutions connectées et écologiques pour la mobilité urbaine.",
    image: "/asset/img/mobilink.png",
    phone: "+216 23 233 444",
    website: "www.mobylink.io",
    link: "/startinc2",
    category: "Mobilité"
  },
  {
    name: "AquaSmart",
    description: "Surveillance en temps réel de la qualité de l'eau.",
    image: "/asset/img/aqua.png",
    phone: "+216 23 567 890",
    website: "www.aquasmart.tn",
    link: "/startinc2",
    category: "Environnement"
  },
  {
    name: "Paint",
    description: "Matériaux de peinture écologiques et innovants.",
    image: "asset/img/paint.png",
    phone: "+216 23 456 789",
    website: "www.paint.com",
    link: "/startinc2",
    category: "Écologie"
  },
  {
    name: "SafeHome",
    description: "Capteurs intelligents pour prévenir les accidents domestiques.",
    image: "/asset/img/safehome.png",
    phone: "+216 24 458 789",
    website: "www.safehome.io",
    link: "/startinc2",
    category: "Domotique"
  },
  {
    name: "RecyTech",
    description: "Recyclage automatisé des déchets électroniques.",
    image: "/asset/img/recy.png",
    phone: "+216 23 403 927",
    website: "www.recytech.tn",
    link: "/startinc2",
    category: "Environnement"
  }
]);

// Filtrage des startups
const filteredStartups = computed(() => {
  return startups.value.filter(s =>
    (!search.value || s.name.toLowerCase().includes(search.value.toLowerCase()) || 
     s.description.toLowerCase().includes(search.value.toLowerCase())) &&
    (!category.value || s.category === category.value)
  );
});
</script>

<style scoped>
/* Styles complémentaires pour améliorer le design */
.form-input:focus, .form-select:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
}

/* Animation pour les cartes */
.transition-transform {
  transition-property: transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

.hover\:-translate-y-2:hover {
  --tw-translate-y: -0.5rem;
  transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
}
</style>