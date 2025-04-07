<script setup>
import { ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Main from "../../Layouts/main.vue";
import 'bootstrap/dist/css/bootstrap.min.css';


</script>

<template>
    <Main :showSidebar="true">
        <div class="max-w-5xl mx-auto mt-8 p-6 bg-white shadow rounded-lg"
            style="background-color: gainsboro;margin-left: 33px;margin-right: -220px;">
            <h1 class="text-2xl font-bold mb-4 text-center">Gestion des Membres</h1>

            <!-- Formulaire d'ajout -->
            <div class="flex justify-between mb-4">
                <input v-model="search" type="text" placeholder="Rechercher un membre..."
                    class="border p-2 w-2/3 rounded" />
                <button    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    + Ajouter un Membre
                </button>
            </div>
            <!-- Recherche -->

            <!-- Table des membres -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                    <thead class="bg-gray-200 text-gray-600">
                        <tr>
                            <th class="py-2 px-4 border">Nom</th>
                            <th class="py-2 px-4 border">Email</th>
                            <th class="py-2 px-4 border">Startup</th>
                            <th class="py-2 px-4 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center border-t">
                            <td class="py-2 px-4"></td>
                            <td class="py-2 px-4"></td>
                            <td class="py-2 px-4"></td>
                            <td class="py-2 px-4">
                               
                            </td>
                        </tr>
                       
                    </tbody>
                </table>
            </div>
        </div>

            
           
        
    </Main>
</template>
<style>
/* Animation pour la modale */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>