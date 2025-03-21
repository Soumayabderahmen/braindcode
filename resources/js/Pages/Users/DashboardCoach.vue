<template>

    <Main  :showSidebar="true">
      <div>
        <p>Bienvenue,{{ $page.props.auth.user.name }} page Coach
            !</p>
      </div>
      <Chatbot />
    </Main>
  </template>
   <script setup>
   import Main from "../../Layouts/Main.vue";
   import Chatbot from '@/Pages/Chatbot/Chatbot.vue';

   const props = defineProps({
     user: Object,
   });
   </script>