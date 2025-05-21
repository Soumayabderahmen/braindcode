<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-blue-100 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-md sm:mx-auto">
      <!-- Logo BrainCode avec effet d'élévation -->
      <div class="absolute inset-0 bg-gradient-to-r from-blue-300 to-blue-600 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-3 sm:rounded-3xl"></div>
      
      <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20" style="padding: 3rem;">
        <!-- Logo centré avec animation hover -->
        <div class="flex justify-center mb-8 transform hover:scale-105 transition-transform duration-300">
          <Link href="#">
            <img src="/image/logos/BraindCode.png" alt="Logo BraindCode" class="h-24 w-auto" style="height: 4rem;"/>
          </Link>
        </div>
        
        <!-- Contenu du slot (formulaire, etc.) -->
        <div class="max-w-md mx-auto">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
:root {
  --primary-color: #005183;
}

/* Animation de pulsation subtile pour le logo */
@keyframes gentle-pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.02); }
  100% { transform: scale(1); }
}

/* Effet d'ombre avancé */
.shadow-custom {
  box-shadow: 0 10px 25px -5px rgba(0, 81, 131, 0.1), 0 10px 10px -5px rgba(0, 81, 131, 0.04);
}
</style>