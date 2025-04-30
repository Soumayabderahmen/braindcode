<script setup>
import { ref } from 'vue'
import Navbar from "../Components/Navbar.vue";
import Sidebar from "../Components/Sidebar.vue";

const isClosed = ref(false)
const toggleSidebar = () => {
  isClosed.value = !isClosed.value
}
</script>

<template>
  <div class="layout">
    <Sidebar :isClosed="isClosed" @toggle="toggleSidebar" />
    <div :class="['main-content', { collapsed: isClosed }]">
      <Navbar />
      <slot />
    </div>
  </div>
</template>

<style scoped>
.layout {
  display: flex;
}

.main-content {
  transition: margin-left 0.3s ease;
  margin-left: 260px;
  width: calc(100% - 260px);
  background: aliceblue;
  
  min-height: 100vh; /* ✅ Ajout ici pour que la couleur couvre tout l'écran */
}

.main-content.collapsed {
  margin-left: 80px;
  width: calc(100% - 80px);
}
</style>

