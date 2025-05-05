<template>
  <div class="layout-wrapper">
    <!-- Sidebar à gauche -->
    <Sidebar :showSidebar="showSidebar" />
    
    <!-- Conteneur principal -->
    <div class="layout-page" :class="{ 'sidebar-open': showSidebar }">
      <!-- Navbar en haut -->
      <Navbar :showSidebar="showSidebar" />
      
      <!-- Contenu principal -->
      <main class="content-wrapper">
        <div class="container-fluid p-4" style="overflow: visible; position: relative; z-index: 1;">
          <slot></slot> <!-- Assurez-vous que le slot est bien rendu -->
        </div>         
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import Sidebar from "../Components/sidebar.vue";

// Définir les propriétés pour contrôler l'affichage
const props = defineProps({
  showSidebar: {
    type: Boolean,
    default: true,
  },
});

const showSidebar = ref(props.showSidebar);

// Fonction pour alterner l'état du sidebar
const toggleSidebar = () => {
  showSidebar.value = !showSidebar.value;
};

// Exposer la fonction pour qu'elle puisse être utilisée par les composants parents
defineExpose({ toggleSidebar });
</script>


<style scoped>
.layout-wrapper {
  display: flex;
  min-height: 100vh;
  background-color: #f0f7ff; /* Couleur de fond bleu clair comme dans l'image */
}

.layout-page {
  flex: 1;
  display: flex;
  flex-direction: column;
  transition: margin-left 0.3s ease;
  left: 16.25rem;
}

/* Si le sidebar est ouvert, on ajoute un décalage */
.layout-page.sidebar-open {
  margin-left: 38px; /* Ajuste la largeur du sidebar */
}

/* Si le sidebar est fermé, les rectangles se déplacent à droite */
.layout-page.sidebar-closed.rectangle-container {
  margin-left: 756px;
position: unset;
}

.content-wrapper {
  flex: 1;
  padding: 1rem;
}

@media (max-width: 992px) {
  .layout-page {
    margin-left: 0;
  }

  /* Sur mobile, on veut également que les rectangles se positionnent à droite */
  .layout-page.sidebar-closed .rectangle-container {
    margin-left: 0;
  }
}

</style>