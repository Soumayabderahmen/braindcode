<template>

    <Main  :showSidebar="true">
      <div>
        <p>Bienvenue,{{ $page.props.auth.user.name }}
            !</p>
      </div>
    </Main>
  </template>
  
  <script setup>
  import Main from "../Layouts/main.vue";
  const props = defineProps({
    user: Object,
  });
  </script>