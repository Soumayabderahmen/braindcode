<template>
<div class="bg-[#eaf6ff] min-h-screen py-10 px-4"style="
    margin-left: -10px;
    margin-right: 0px;
">
  <!-- Couverture -->
  <div class="w-full h-64"style="height: 208px;">
    <img :src="coverPreview" class="w-full h-full object-cover" alt="banner" />
    <button
    @click="$refs.coverInput.click()"
    class="absolute top-4 right-6 bg-white bg-opacity-80 p-2 rounded-full shadow-md hover:bg-opacity-100 transition"
    title="Modifier la bannière"
  >
    <!-- Icône crayon SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#003366]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15.232 5.232l3.536 3.536M9 11l3.536-3.536M5 19h2l9.536-9.536" />
    </svg>
   
  </button>
  </div>

  <!-- Profil -->
  <div class="max-w-6xl mx-auto flex justify-between items-start px-6 py-6 bg-[#eaf6ff]">
    <!-- Avatar + Infos -->
    <div class="flex items-center space-x-6"style="margin-top: -73px;">
      <img
       :src="avatarPreview"
        class="w-[174px] h-[169px] rounded-xl   shadow object-cover"
        alt="avatar"
        style="height: 177px; width: 171px;"
      />
      <div style="
    margin-left: 17px;"
      @click="showModal = true"

>
<div
  v-if="showModal"
  class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50"
>
  <div class="relative bg-white rounded-lg overflow-hidden shadow-lg max-w-xl">
    <!-- Bouton fermer -->
    <button
      @click="showModal = false"
      class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-2xl font-bold"
    >
      &times;
    </button>

    <!-- Image agrandie -->
    <img
      :src="avatarPreview"
      alt="image agrandie"
      class="w-full max-h-[80vh] object-contain"
    />
  </div>
</div>
 
        <h2 class="text-2xl font-bold text-[#003366]">{{user.name}}</h2>
        <b class="text-sm text-gray-700 flex items-center">
 {{ user.specialty }}

</b>
<p class="text-sm text-gray-700 flex items-center">
  500 points
  <img src="/assets/img/profile/badge.png" alt="badge" class="ml-2 w-6 h-6" />
  <img src="/assets/img/profile/badge.png" alt="badge" class="ml-2 w-6 h-6" />
  <img src="/assets/img/profile/badge.png" alt="badge" class="ml-2 w-6 h-6" />

</p>

       
      </div>
    </div>

    <!-- Boutons -->
    <div class="space-x-2 mt-6">
      <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"style="background-color:#0093ee;margin-right: 27px;font-size: 16px;
color: #fff;
font-family: Poppins;">Modifier</button>
      <button class="border border-blue-500 text-blue-500 px-4 py-2 rounded  hover:text-white" style="border: 1px solid #0093ee;font-size: 16px;
color: #0093ee;
font-family: Poppins;
box-sizing: border-box;">Supprimer</button>
    </div>
  </div>
 <b style="
    margin-left: 56px;
">----------------------------------------------------</b> 
  <!-- Formulaire -->
  <div class="max-w-5xl mx-auto bg-white p-6 mt-6 rounded-xl shadow">
    <h3 class="text-lg font-bold text-[#003366] mb-4">Modifier Coach</h3>
    <form class="bg-[#eaf6ff] p-6 rounded-xl space-y-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold mb-1 text-[#003366]">Nom</label>
          <input class="w-full p-2 rounded border bg-white border-gray-300" placeholder="Écrire ici..." />
        </div>
        <div>
          <label class="block font-semibold mb-1 text-[#003366]">Prénom</label>
          <input class="w-full p-2 rounded border bg-white border-gray-300" placeholder="Écrire ici..." />
        </div>
      </div>

      <div>
        <label class="block font-semibold mb-1 text-[#003366]">Email</label>
        <input class="w-full p-2 rounded border bg-white border-gray-300" placeholder="Écrire ici..." />
      </div>

      <div>
        <label class="block font-semibold mb-1 text-[#003366]">Numéro</label>
        <input class="w-full p-2 rounded border bg-white border-gray-300" placeholder="Écrire ici..." />
      </div>

      <div>
        <label class="block font-semibold mb-1 text-[#003366]">Domaine</label>
        <input class="w-full p-2 rounded border bg-white border-gray-300" placeholder="Écrire ici..." />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold mb-1 text-[#003366]">Adresse</label>
          <input class="w-full p-2 rounded border bg-white border-gray-300" placeholder="Écrire ici..." />
        </div>
        <div>
          <label class="block font-semibold mb-1 text-[#003366]">Code postal</label>
          <input class="w-full p-2 rounded border bg-white border-gray-300" placeholder="Écrire ici..." />
        </div>
      </div>

      <div class="text-right">
        <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Sauvegarder</button>
      </div>
    </form>
  </div>
</div>

     
  </template>
  
  <script setup>
  import { reactive, ref } from 'vue';
  
  const props = defineProps({
  user: Object,
  mustVerifyEmail: Boolean,
  status: String,
  role: String,
});
  const form = reactive({
    nom: props.user?.name || '',
    prenom: props.user?.surname || '',
    email: props.user?.email || '',
    phone: props.user?.phone || '',
    domaine: props.user?.domain || '',
    adresse: props.user?.address || '',
    code_postal: props.user?.postal_code || '',
  });
  const avatarPreview = ref(props.user.coach?.profile_image
  ? `/storage/${props.user.coach.profile_image}`
  : '/assets/img/profile/default-avatar.jpg');

const coverPreview = ref(props.user.coach?.cover_image
  ? `/storage/${props.user.coach.cover_image}`
  : '/assets/img/profile/banner.jpg');

  const submitForm = () => {
    console.log('Formulaire soumis:', form);
  };
  const showModal = ref(false)
  </script>
  
  <style scoped>
  input::placeholder {
    color: #999;
  }
  </style>
  