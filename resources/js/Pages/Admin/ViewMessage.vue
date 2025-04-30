<script setup>
// import { Link } from '@inertiajs/vue3';
// import Main from '@/Layouts/Main.vue';
import { route } from 'ziggy-js';

const props = defineProps({
    message: Object
});
</script>

<template>
    
        <div class="message-container">
            <h1 class="text-2xl font-bold mb-4">Détail du Message</h1>

            <p><strong>Nom :</strong> {{ message.name }}</p>
            <p><strong>Email :</strong> {{ message.email }}</p>
            <p><strong>Catégorie :</strong> {{ message.category }}</p>
            <p><strong>Message :</strong> {{ message.message }}</p>

            <div v-if="message.file_path" class="mt-4">
                <strong>Fichier joint :</strong>
                <a :href="'/storage/' + message.file_path" target="_blank" class="text-blue-600 hover:underline">Télécharger</a>
            </div>

            <a :href="route('admin.support.messages')" class="mt-4 inline-block text-gray-600 hover:underline">

                Retour à la liste
            </a>
        </div>

</template>
<style scoped>
.message-container {
  margin: 20px auto;
  padding: 24px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  width: 90%;
  max-width: 900px;
}
</style>

