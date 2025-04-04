<script setup>
import { ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Main from "../../Layouts/main.vue";
import 'bootstrap/dist/css/bootstrap.min.css';

defineProps({ membres: Array });

const search = ref("");
const showModal = ref(false);

const filteredMembres = computed(() => {
    return search.value
        ? membres.filter((m) =>
            m.user.name.toLowerCase().includes(search.value.toLowerCase())
        )
        : membres;
});

const deleteMembre = (id) => {
    if (confirm("Voulez-vous vraiment supprimer ce membre ?")) {
        router.delete(route("membres.destroy", id));
    }
};

const form = useForm({
    name: "",
    email: "",
    password: "",
    startup_id: "",
});

const submit = () => {
    form.post(route("membres.store"), {
        onSuccess: () => form.reset(),
    });
};
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
                <button @click="showModal = true"     class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
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
                                <!-- <button @click="deleteMembre(membre.id)" class="bg-red-500 text-white px-2 py-1 rounded">
                Supprimer
              </button> -->
                            </td>
                        </tr>
                        <!-- <tr v-if="filteredMembres.length === 0">
            <td colspan="4" class="py-4 text-gray-500">Aucun membre trouvé.</td>
          </tr> -->
                    </tbody>
                </table>
            </div>
        </div>

            <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg w-96">
                    <!-- Header -->
                    <div class="flex justify-between items-center border-b px-4 py-3">
                        <h5 class="text-lg font-semibold">Titre de la modale</h5>
                        <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                            ✖
                        </button>
                    </div>
                    <form @submit.prevent="submit" class="space-y-4" style="
width: 568px;
               
">

                        <input v-model="form.name" type="text" placeholder="Nom"
                        class="mt-1 block w-full" />
                        <input v-model="form.email" type="email" placeholder="Email"
                            class="border p-2 w-full rounded shadow" />
                        <input v-model="form.password" type="password" placeholder="Mot de passe"
                            class="border p-2 w-full rounded shadow" />
                        

                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" @click="showModal = false"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
                                Annuler
                            </button>
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow">
                                Ajouter
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="flex justify-end border-t px-6 py-4 space-x-3" style="
    margin-bottom: 23px;
">
                <button @click="showModal = false" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    Fermer
                </button>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Sauvegarder
                </button>
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