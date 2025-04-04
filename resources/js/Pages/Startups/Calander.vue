<script setup>
import { ref, computed } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import Main from "../../Layouts/main.vue";
import 'bootstrap/dist/css/bootstrap.min.css';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  availabilities: Array,
  coachs: Array,
});

const selectedCoachId = ref(null); // Valeur initiale pour aucun coach sélectionné

// Options du calendrier
const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay',
  },
  events: [], // Initialement vide
  editable: true,
  selectable: true,
  selectMirror: true,
  dayMaxEvents: true,
  weekends: true,
  eventClick: async function(info) {
  const event = info.event;
  
  if (!selectedCoachId.value) {
    alert('Veuillez sélectionner un coach');
    return;
  }

  if (event.extendedProps.statut === 'available') {
    try {
      const params = {
        coach_id: Number(selectedCoachId.value),
        date: event.startStr.substring(0, 10),
        availability_id: Number(event.id)
      };

      await router.visit('/startup/reservations/create', {
        method: 'get',
        data: params,
        onSuccess: () => console.log('Redirection réussie'),
        onError: (errors) => {
          console.error('Erreurs:', errors);
          alert('Données invalides - Veuillez réessayer');
        }
      });
    } catch (error) {
      console.error('Erreur complète:', error);
    }
  }
}
});
// Fonction pour charger les disponibilités du coach sélectionné
function loadCoachAvailabilities() {
  if (!selectedCoachId.value) {
    // Si aucun coach n'est sélectionné, afficher un calendrier vide
    calendarOptions.value.events = [];
    return;
  }

  // Filtrer les disponibilités du coach sélectionné
  const coachAvailabilities = props.availabilities.filter(
    (avail) => avail.coach_id === selectedCoachId.value
  );

  // Mettre à jour les événements du calendrier
  calendarOptions.value.events = coachAvailabilities.map((avail) => ({
    id: avail.id,
    title: avail.statut === 'available' ? 'Disponible' : 'Indisponible',
    start: `${avail.date}T${avail.start_time}`,
    end: `${avail.date}T${avail.end_time}`,
    color: avail.statut === 'available' ? '#28a745' : '#dc3545',
    extendedProps: {
      statut: avail.statut,
    },
  }));
}

// Calculer dynamiquement le nom du coach sélectionné
const selectedCoachName = computed(() => {
  const coach = props.coachs.find(coach => coach.id === selectedCoachId.value);
  return coach ? coach.name : 'Aucun Coach'; // Affiche 'Aucun Coach' si aucun coach n'est sélectionné
});
</script>

<template>
  <Main :showSidebar="true">
    <div>
      <!-- Sélecteur de coach -->
      <div class="mb-4">
        <h6 for="coachSelect" class="form-label">Sélectionner un Coach</h6>
        <select id="coachSelect" v-model.number="selectedCoachId" 
          class="form-select form-select-lg mb-3"
          aria-label="Large select example"
          @change="loadCoachAvailabilities"
          >
          <option :value="null" disabled>Choisir un coach</option> <!-- 👈 ici on met null -->
          <option v-for="coach in props.coachs" :key="coach.id" :value="coach.id">
            {{ coach.name }}
          </option>
        </select>

      </div>

      <!-- Calendrier -->
      <div class="card-body" style="margin-right: -247px;">
        <div class="row">
          <div class="col-md-12">
            <div id="right">
              <div class="card-header">
                <h2 class="card-title text-center mb-4">Le Calendrier de {{ selectedCoachName }}</h2>
              </div>
              <div class="card-body">
                <FullCalendar :options="calendarOptions" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Main>
</template>

<style scoped>
.calendar-container {
  max-width: 900px;
  margin: auto;
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

:deep(.fc-toolbar) {
  background: #34495F;
  color: white;
  border-radius: 8px;
  padding: 10px;
}

:deep(.fc-button) {
  background: #2980b9 !important;
  border: none !important;
  color: white !important;
}

:deep(.fc-daygrid-day) {
  border: 1px solid #ddd;
}

:deep(.fc-daygrid-day-number) {
  color: #2c3e50;
  font-weight: bold;
}
</style>
