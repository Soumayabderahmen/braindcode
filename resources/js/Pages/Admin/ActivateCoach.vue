<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    coaches: Array
});

const search = ref("");

// Pagination
const currentPage = ref(1);
const perPage = 4;
const meetingTimeFilter = ref("");


const filteredinvestisseurs = computed(() => {
  return props.coaches.filter((r) => {
    const queryMatch =
      !search.value ||
      r.email?.toLowerCase().includes(search.value.toLowerCase()) ||
      r.name?.toLowerCase().includes(search.value.toLowerCase());

    const meetingTimeMatch =
      !meetingTimeFilter.value || r.statut === meetingTimeFilter.value;

   
    return queryMatch && meetingTimeMatch ;
  });
});
const totalPages = computed(() => Math.ceil(filteredinvestisseurs.value.length / perPage));
const paginatedinvestisseurs = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return filteredinvestisseurs.value.slice(start, start + perPage);
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const activateCoach = (coachId) => {
    window.location.href = `/admin/activate-coach/${coachId}`;
};
</script>

<template>
     

     <div class="card card-1 cardDash">
    <div class="card-header d-lg-flex d-md-flex d-sm-flex d-block">
      <h5>Liste des Coaches </h5>

    </div>
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
            Statut</option>
          <option v-for="r in [...new Set(props.coaches.map(res => res.statut))]" :key="r" :value="r">
            {{ r }}
          </option>
       
          </select>
        </div>
      </div>

      <span class="text-sm text-gray-500 ml-4">{{ filteredinvestisseurs.length }} résultat(s)</span>
    </div>
    <div class="card-body">
      <div class="col-12 table-responsive">
        <table id="avancementsTable" class="table table-1 w-100">
          <thead>
          <tr>
            <th><center>Nom</center></th>
          <th ><center>Email</center></th>
          <th><center>Specialité</center></th>
            <th ><center>Statut</center></th>
            <th ><center>Actions</center></th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="filteredinvestisseurs.length === 0">
            <td colspan="4" class="text-center py-6 text-gray-500">Aucun coach trouvé</td>
          </tr>
          <tr v-for="coach in filteredinvestisseurs" :key="coach.id" >
            <td ><center><span>{{ coach.name }}</span></center></td>
            <td ><center><span>{{ coach.email }}</span></center></td>
            <td ><center><span>{{ coach.specialty }}</span></center></td>

            <td style="text-align: center;">
                <span :class="[
                  'px-7 py-1 inline-flex  rounded-full font-semibold',
                  coach.statut === 'active'
                    ? 'bg-green-100 text-green-800'
                    : coach.statut === 'inactive'
                      ? 'bg-red-100 text-red-800'

                      : 'bg-yellow-100 text-yellow-800'
                ]">

                  <center> {{ coach.statut }}</center>
                </span>

              </td>
            <td >
              <center>
              <button 
                v-if="coach.statut !== 'active'" 
                @click="activateCoach(coach.id)" 
                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
              >
                Activer
              </button>
              <span v-else class="text-gray-500">Activé</span>
            </center>
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
</div>
</template>
