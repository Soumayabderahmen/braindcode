<template>
  <section class="tutorial-hero relative overflow-hidden py-16 bg-gradient-to-r from-blue-50 to-indigo-50">
    <!-- Background overlay pattern -->
    <div class="absolute inset-0 opacity-10 z-0">
      <div class="absolute inset-0" style="background-image: url('/asset/img/tuto.png'); background-size: cover; background-position: center; filter: blur(1px);"></div>
      <div class="absolute inset-0 bg-gradient-to-br from-blue-500/30 to-indigo-600/30"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
      <!-- En-tête avec animation subtile -->
      <div class="mb-12 text-center sm:text-left max-w-3xl mx-auto sm:mx-0">
        <span class="inline-block px-4 py-1 rounded-full bg-blue-100 text-blue-700 font-medium text-sm mb-4">
          Guide complet
        </span>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
          Tutoriel de la Plateforme <span class="text-blue-600">BrainCode</span>
        </h1>
        <p class="text-lg text-gray-600 leading-relaxed">
          Découvrez comment tirer le meilleur parti de notre écosystème collaboratif qui connecte startups, coachs et investisseurs.
        </p>
      </div>

      <!-- Carte contenant les espaces avec style moderne -->
      <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 max-w-4xl mx-auto">
        <div class="text-center sm:text-left mb-8">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">Choisissez votre espace</h2>
          <p class="text-gray-600">
            Sélectionnez votre profil pour accéder au tutoriel personnalisé selon votre rôle dans l'écosystème BrainCode.
          </p>
        </div>

        <!-- Grid des espaces avec hover effects -->
        <div class="grid md:grid-cols-3 gap-4 md:gap-6">
          <!-- Espace Startup -->
          <div class="space-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-5 transition-all duration-300 hover:shadow-md hover:translate-y-1 border border-blue-200 group cursor-pointer">
            <div class="bg-blue-500 text-white p-3 rounded-full inline-flex items-center justify-center w-12 h-12 mb-4 group-hover:bg-blue-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Espace Startup</h3>
            <p class="text-sm text-gray-600 mb-4">Apprenez à présenter votre projet et à trouver des partenaires.</p>
            <a href="#" class="inline-flex items-center text-blue-600 font-medium text-sm group-hover:text-blue-700">
              Découvrir
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>

          <!-- Espace Coach -->
          <div class="space-card bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-5 transition-all duration-300 hover:shadow-md hover:translate-y-1 border border-indigo-200 group cursor-pointer">
            <div class="bg-indigo-500 text-white p-3 rounded-full inline-flex items-center justify-center w-12 h-12 mb-4 group-hover:bg-indigo-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Espace Coach</h3>
            <p class="text-sm text-gray-600 mb-4">Gérez vos sessions et accompagnez efficacement les startups.</p>
            <a href="#" class="inline-flex items-center text-indigo-600 font-medium text-sm group-hover:text-indigo-700">
              Découvrir
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>

          <!-- Espace Investisseur -->
          <div class="space-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-5 transition-all duration-300 hover:shadow-md hover:translate-y-1 border border-purple-200 group cursor-pointer">
            <div class="bg-purple-500 text-white p-3 rounded-full inline-flex items-center justify-center w-12 h-12 mb-4 group-hover:bg-purple-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Espace Investisseur</h3>
            <p class="text-sm text-gray-600 mb-4">Explorez des projets innovants et gérez vos investissements.</p>
            <a href="#" class="inline-flex items-center text-purple-600 font-medium text-sm group-hover:text-purple-700">
              Découvrir
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Section d'aide supplémentaire -->
        <div class="mt-8 pt-6 border-t border-gray-100">
          <div class="flex flex-col sm:flex-row items-center justify-between">
            <div class="mb-4 sm:mb-0">
              <p class="text-gray-500 text-sm">Besoin d'aide supplémentaire?</p>
              <a href="/contact" class="text-blue-600 hover:text-blue-700 font-medium">Consulter notre documentation</a>
            </div>
        
            <a href="/contact" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors inline-flex items-center">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
  </svg>
  Contacter le support
</a>

          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// Le script reste minimal comme dans la version d'origine
// import { route } from '@inertiajs/vue3'
</script>

<style scoped>
/* Animations subtiles et transitions */
.tutorial-hero {
  position: relative;
  background-attachment: fixed;
}

.space-card {
  transform-origin: center bottom;
}
</style>