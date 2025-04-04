<template>
    <Navbar   />
    <Sidebar  />
    <main class="p-6 mx-auto max-w-screen-lg">
      <slot />
    </main>
  </template>
  
  <script setup>
  import Navbar from "../Components/navbar.vue";
  import Sidebar from "../Components/sidebar.vue";

  // Définir les propriétés pour contrôler l'affichage
  const props = defineProps({
    showSidebar: {
      type: Boolean,
      default: false,
    },
  
    
  
  });
  
  </script>
  
 