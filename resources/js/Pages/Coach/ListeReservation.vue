<script setup>
import { ref, computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Main from "../../Layouts/main.vue";
import 'bootstrap/dist/css/bootstrap.min.css';

const props = defineProps({
    reservations: Array,
});

const search = ref("");

// Filtrage dynamique
const filteredReservations = computed(() => {
    if (!search.value) return props.reservations;
    return props.reservations.filter((r) =>
        r.user?.name?.toLowerCase().includes(search.value.toLowerCase()) ||
        r.startup?.name?.toLowerCase().includes(search.value.toLowerCase())
    );
});
</script>

<template>
    <Main :showSidebar="true">
        <div class="p-6 bg-white shadow rounded-lg" style="width: 79vw; margin-left: 7px;">
            <h1 class="text-2xl font-bold mb-4 text-center">Liste des Réservations</h1>

            <!-- Barre de recherche -->
            <div class="flex justify-between mb-4">
                <input v-model="search" type="text" placeholder="Rechercher une Réservation..."
                    class="border p-2 w-2/3 rounded" />
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                    <thead class="bg-gray-200 text-gray-600 text-center border-t">
                        <tr>
                            <th class="py-2 px-4 border">Nom du Coach</th>
                            <th class="py-2 px-4 border">Nom du Startup</th>
                            <th class="py-2 px-4 border">Statut</th>
                            <th class="py-2 px-4 border">le Temps de Réunion </th>
                            <th class="py-2 px-4 border">Durée</th>
                            <th class="py-2 px-4 border">Date de Creation</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="reservation in filteredReservations" :key="reservation.id"
                            class="text-center border-t">
                            <td class="py-2 px-4">{{ reservation.coach?.user.name }}</td>
                            <td class="py-2 px-4">{{ reservation.startup?.user.name }}</td>
                            <td class="py-2 px-4">
                                <span v-if="reservation.statut === 'en attente'"   style="background-color: darkgray; padding: 5px 10px; border-radius: 50px; color: white;">
                                    En
                                    attente</span>
                                <span v-else-if="reservation.statut === 'acceptée'"
                                style="background-color: #119b5bbf; padding: 5px 10px; border-radius: 50px; color: white;">Acceptée</span>
                                <span v-else-if="reservation.statut === 'refuser'"
                                    class="label label-danger">Refusée</span>
                                <span v-else class="label label-warning">Inconnu</span>
                            </td>
                            <td class="py-2 px-4">{{ reservation.meeting_time }}</td>
                            <td class="py-2 px-4">{{ reservation.duration }} min</td>
                            <td class="py-2 px-4"> {{ new Date(reservation.created_at).toLocaleDateString('fr-FR') }}</td>

                        </tr>
                        <tr v-if="filteredReservations.length === 0">
                            <td colspan="3" class="text-center py-4">Aucune réservation trouvée.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Main>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
