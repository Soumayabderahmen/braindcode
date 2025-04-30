<script setup>
import { ref, computed } from "vue";
const props = defineProps({
  reservations: Array,
});

// Rechercher
const search = ref("");

// Pagination
const currentPage = ref(1);
const perPage = 5;

const filteredReservations = computed(() => {
  if (!search.value) return props.reservations;

  const query = search.value.toLowerCase();
  return props.reservations.filter((r) =>
    r.coach?.user?.name?.toLowerCase().includes(query) ||
    r.startup?.user?.name?.toLowerCase().includes(query)
  );
});

// Pagination calculée
const totalPages = computed(() => Math.ceil(filteredReservations.value.length / perPage));
const paginatedReservations = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return filteredReservations.value.slice(start, start + perPage);
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};
</script>

<template>
  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow" style="width: 79vw; margin-left: 7px;">
    <h1 class="text-2xl font-bold p-6 text-center">Liste des Réservations</h1>

    <!-- Barre de recherche -->
    <div class="px-6 pb-4 flex justify-between items-center">
      <input
        v-model="search"
        type="text"
        placeholder="Rechercher une réservation..."
        class="border border-gray-300 rounded-md p-2 w-2/3 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <span class="text-sm text-gray-500 ml-4">{{ filteredReservations.length }} résultat(s)</span>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Coach</th> -->
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Startup</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temps de Réunion</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de Création</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="paginatedReservations.length === 0" class="hover:bg-gray-50">
            <td colspan="6" class="px-6 py-10 text-sm text-center text-gray-500">
              <div class="flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-lg font-medium">Aucune réservation trouvée</span>
              </div>
            </td>
          </tr>
          <tr v-for="reservation in paginatedReservations" :key="reservation.id" class="hover:bg-gray-50">
            <!-- <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ reservation.coach?.user.name }}</td> -->
            <td class="px-6 py-4 text-sm text-gray-800">{{ reservation.startup?.user.name }}</td>
            <td class="px-6 py-4 text-sm">
              <span
                :class="[
                  'px-2 py-1 inline-flex text-xs leading-5 rounded-full font-semibold',
                  reservation.statut === 'acceptée'
                    ? 'bg-green-100 text-green-800'
                    : reservation.statut === 'refusée'
                    ? 'bg-red-100 text-red-800'
                    : reservation.statut === 'en attente'
                    ? 'bg-gray-200 text-gray-800'
                    : 'bg-yellow-100 text-yellow-800'
                ]"
              >
                {{ reservation.statut }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-800">{{ reservation.meeting_time }}</td>
            <td class="px-6 py-4 text-sm text-gray-800">{{ reservation.duration }} min</td>
            <td class="px-6 py-4 text-sm text-gray-500">
              {{ new Date(reservation.created_at).toLocaleDateString('fr-FR') }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="flex justify-center items-center space-x-2 py-4">
      <button
        class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
        :disabled="currentPage === 1"
        @click="goToPage(currentPage - 1)"
      >
        Précédent
      </button>

      <span class="text-sm text-gray-600">Page {{ currentPage }} sur {{ totalPages }}</span>

      <button
        class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
        :disabled="currentPage === totalPages"
        @click="goToPage(currentPage + 1)"
      >
        Suivant
      </button>
    </div>
  </div>
</template>
