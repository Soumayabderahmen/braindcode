// Main.vue
<template>
  <div class="dashboard-layout">
    <Sidebar />
    <div class="main-content">
      <Navbar />
      <main class="p-6 mx-auto max-w-screen-lg">
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
import Navbar from "../Components/Navbar.vue";
import Sidebar from "../Components/Sidebar.vue";

export default {
  components: {
    Sidebar,
    Navbar,
  },
};
</script>

<style scoped>
.dashboard-layout {
  display: flex;
}
.main-content {
  margin-left: 260px; /* Alignement avec le sidebar */
  width: calc(100% - 260px);
}
</style>