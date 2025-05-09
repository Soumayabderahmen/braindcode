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
const meetingTimeFilter = ref("");
const durationFilter = ref("");

const filteredReservations = computed(() => {
  return props.reservations.filter((r) => {
    const queryMatch =
      !search.value ||
      r.statut?.toLowerCase().includes(search.value.toLowerCase()) ||
      r.startup?.user?.name?.toLowerCase().includes(search.value.toLowerCase());

    const meetingTimeMatch =
      !meetingTimeFilter.value || r.meeting_time === meetingTimeFilter.value;

    const durationMatch =
      !durationFilter.value || r.duration === parseInt(durationFilter.value);

    return queryMatch && meetingTimeMatch && durationMatch;
  });
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
      <div class="px-6 pb-4 flex justify-between items-center">
    <div
      class="col-lg-8 col-md-8 col-sm-8 col-12 d-lg-flex d-md-flex d-sm-flex d-block justify-content-start align-items-center">
      <div class="input-group me-8 mb-4 " style="width: 247px;">
        <span class="input-group-text">
          <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
            <path
              d="M17.8749 18L12.4244 12.3333M1.52344 7.61111C1.52344 8.47929 1.68792 9.33898 2.00748 10.1411C2.32705 10.9432 2.79544 11.672 3.38592 12.2859C3.9764 12.8998 4.6774 13.3867 5.4489 13.719C6.22039 14.0512 7.04728 14.2222 7.88234 14.2222C8.71741 14.2222 9.54429 14.0512 10.3158 13.719C11.0873 13.3867 11.7883 12.8998 12.3788 12.2859C12.9692 11.672 13.4376 10.9432 13.7572 10.1411C14.0768 9.33898 14.2412 8.47929 14.2412 7.61111C14.2412 6.74293 14.0768 5.88325 13.7572 5.08115C13.4376 4.27905 12.9692 3.55025 12.3788 2.93635C11.7883 2.32245 11.0873 1.83548 10.3158 1.50324C9.54429 1.171 8.71741 1 7.88234 1C7.04728 1 6.22039 1.171 5.4489 1.50324C4.6774 1.83548 3.9764 2.32245 3.38592 2.93635C2.79544 3.55025 2.32705 4.27905 2.00748 5.08115C1.68792 5.88325 1.52344 6.74293 1.52344 7.61111Z"
              stroke="#C6C6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </span>
        <input v-model="search" type="text" class="form-control  " placeholder="Rechercher">
      </div>
      <div class="d-lg-flex d-md-flex d-sm-flex d-block">
        <select v-model="meetingTimeFilter" class="form-select mb-4 me-8" style="
    border-radius: 15px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    padding: 13px 35px;
    width: 180px;
">

          <option value="">

            🕒
            les heures</option>
          <option v-for="r in [...new Set(props.reservations.map(res => res.meeting_time))]" :key="r" :value="r">
            {{ r }}
          </option>
        </select>

        <select v-model="durationFilter" class="form-select mb-4" style="
    border-radius: 15px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    padding: 13px 35px;
    width: 180px;
">
          <option value="">⏳ les durées</option>
          <option v-for="r in [...new Set(props.reservations.map(res => res.duration))]" :key="r" :value="r">
            {{ r }} minutes
          </option>
        </select>
      </div>
    </div>

    <span class="text-sm text-gray-500 ml-4">{{ filteredReservations.length }} résultat(s)</span>
  </div>
  <div class="card-body">
    
      <div class="flex flex-col gap-4 mt-4">
        <div class="flex items-center px-6 py-3 font-semibold text-sm" style=" border-radius: 12px;">

          <div class="w-1/3 text-center" style="color: #005183;"><b>Coach</b></div>
          <div class="w-1/3 text-center" style="color: #005183;"><b>Statut</b></div>
          <div class="w-1/3 text-center" style="color: #005183;"><b>Heure de réunion</b></div>
          <div class="w-1/3 text-center" style="color:#005183;margin-right: 4px;"><b>Durée</b></div>
          <div class="w-1/5 text-center" style="color: #005183;"><b>Date de Création</b></div>
        </div>
        <div v-for="reservation in paginatedReservations" :key="reservation.id"
          class="flex items-center justify-between bg-white rounded-xl  px-6 py-6 hover:shadow-md transition" style="
    height: 70px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    border-radius: 15px;
">
          <!-- Logo ou initiale -->
          <div
            class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-lg font-bold text-gray-700">
            {{ reservation.coach?.user.name.charAt(0).toUpperCase() }}
          </div>

          
          <div class="w-28 text-center text-sm " style="
    color: #0093EE;
">
            {{ reservation.coach?.user.name }}
          </div>

          <!-- Statut badge -->
          <div class="w-28 text-center" style="
    width: 141px;
">
            <span class="px-4 py-1 rounded-full text-sm font-semibold" :class="{
              'bg-green-100 text-green-800': reservation.statut === 'acceptée',
              'bg-red-100 text-red-800': reservation.statut === 'refusée',
              'bg-gray-200 text-gray-800': reservation.statut === 'en attente',
              'bg-yellow-100 text-yellow-800': reservation.statut === 'incubée',
              'bg-gray-300 text-gray-800': reservation.statut === 'accélérer',
            }">
              {{ reservation.statut }}
            </span>
          </div>

          <!-- Temps de réunion -->
          <div class="w-28 text-center text-sm " style="
    color: #0093EE;
">
            {{ reservation.meeting_time }}
          </div>

          <!-- Durée -->
          <div class="w-20 text-center  " style="
    color: #0093EE;
">
            {{ reservation.duration }} min
          </div>

          <!-- Date -->
          <div class="w-28 text-center  " style="
    color: #0093EE;
">
            {{ new Date(reservation.created_at).toLocaleDateString('fr-FR') }}
          </div>

          <!-- Bouton Voir plus -->

        </div>

        <!-- Aucun résultat -->
        <div v-if="paginatedReservations.length === 0" class="bg-white rounded-xl p-6 text-center  shadow">
          <div class="flex flex-col items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-lg font-medium">Aucune réservation trouvée</span>
          </div>
        </div>
      </div>
   

    <!-- Pagination -->
    <!-- Pagination style -->
  <!-- Pagination personnalisée -->
<div v-if="totalPages > 1" class="flex justify-center items-center gap-4 mt-6"style="
    margin-left: 992px;
    margin-top: 43px;
">
  <!-- Précédent -->
  <button
  @click="goToPage(currentPage - 1)"
  :disabled="currentPage === 1"
  class="w-10 h-10 rounded-[10px] border-2 border-[#29B6F6] text-[#0288D1] flex items-center justify-center text-xl font-light shadow-md hover:shadow-lg transition disabled:opacity-40 disabled:cursor-not-allowed"
>
  &lt;
</button>



  <!-- Pages -->
  <div class="flex items-center gap-3 text-sm">
    <button
      v-for="page in totalPages"
      :key="page"
      @click="goToPage(page)"
      :class="[
        'w-6 h-6 text-center',
        page === currentPage ? 'text-[#0288D1] font-semibold' : 'text-gray-400'
      ]"
    >
      {{ page }}
    </button>
  </div>

  <!-- Suivant -->
  <button
    @click="goToPage(currentPage + 1)"
    :disabled="currentPage === totalPages"
    class="w-10 h-10 rounded-[10px] border-2 border-[#29B6F6]  text-[#0288D1] flex items-center justify-center text-xl font-light shadow-md hover:shadow-lg transition disabled:opacity-40 disabled:cursor-not-allowed"
    >
    &gt;
  </button>
</div>


  </div>

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
