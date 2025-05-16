<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-sky-100 py-16 px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl">
      <div
        v-for="(role, index) in roles"
        :key="role.name"
        class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 flex flex-col"
        :class="`animate-fade-in-up`"
        :style="`animation-delay: ${index * 150}ms`"
      >
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 flex justify-center">
          <div class="w-20 h-20 rounded-full bg-white p-2 shadow-md transform -translate-y-2">
            <img :src="role.icon" :alt="role.name" class="w-full h-full object-contain" />
          </div>
        </div>
        
        <div class="p-6 flex-1 flex flex-col">
          <h3 class="text-2xl font-bold mb-3 text-gray-800">{{ role.title }}</h3>
          <p class="text-gray-600 mb-6 flex-1">{{ role.description }}</p>
          
          <Link 
            :href="route('register.' + role.name)" 
            class="group w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium px-6 py-3 rounded-xl transition-all duration-300 flex items-center justify-center space-x-2 hover:shadow-lg"
          >
            <span>S'inscrire</span>
            <svg 
              class="w-5 h-5 transform transition-transform duration-300 group-hover:translate-x-1" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </Link>
        </div>
        
        <div class="absolute top-0 right-0 mt-4 mr-4">
          <div class="bg-white rounded-full p-1 shadow-sm">
            <svg 
              v-if="role.name === 'investisseur'" 
              class="w-5 h-5 text-blue-500" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg 
              v-if="role.name === 'coach'" 
              class="w-5 h-5 text-blue-500" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <svg 
              v-if="role.name === 'startup'" 
              class="w-5 h-5 text-blue-500" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const roles = [
  {
    name: 'investisseur',
    title: 'Investisseur',
    icon: 'asset/img/cnx1.png', 
    description: 'Investissez dans des projets innovants et soutenez les startups prometteuses de demain.',
  },
  {
    name: 'coach',
    title: 'Coach',
    icon: 'asset/img/cnx2.png',
    description: 'Aidez les startups à réussir en offrant vos compétences et conseils de manière flexible et personnalisée.',
  },
  {
    name: 'startup',
    title: 'Startup',
    icon: 'asset/img/cnx3.png',
    description: 'Rejoignez un écosystème dynamique et faites croître votre startup avec des outils adaptés à vos besoins.',
  },
]
</script>

<style scoped>
.animate-fade-in-up {
  opacity: 0;
  animation: fadeInUp 0.8s ease forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>