<script setup>
import { ref, computed, watch } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
// import 'bootstrap/dist/css/bootstrap.min.css';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  availabilities: Array,
  coachs: Array,
});

const selectedSpecialty = ref(null);

// Liste des domaines uniques
const specialties = computed(() => {
  return [...new Set(props.coachs.map(coach => coach.specialty))];
});


// Coachs filtrés selon le domaine sélectionné
const filteredCoachs = computed(() => {
  if (!selectedSpecialty.value) return [];
  return props.coachs.filter(coach => coach.specialty === selectedSpecialty.value);
});

// Charger les disponibilités des coachs du domaine sélectionné
function loadDomainAvailabilities() {
  if (!selectedSpecialty.value) {
    calendarOptions.value.events = [];
    return;
  }

  const coachIds = filteredCoachs.value.map(coach => coach.coach_id); // ID coachs
  const domainAvailabilities = props.availabilities.filter(
    (avail) => coachIds.includes(avail.coach_id) && avail.statut === 'available'
  );

  calendarOptions.value.events = domainAvailabilities.map((avail) => {
    const coach = props.coachs.find(c => c.coach_id === avail.coach_id);
    return {
      id: avail.id,
      title: `Coach: ${coach?.name || 'Inconnu'}`,
      start: `${avail.date}T${avail.start_time}`,
      end: `${avail.date}T${avail.end_time}`,
      color: '#28a745',
      extendedProps: {
        coach_id: avail.coach_id,
        statut: avail.statut,
      },
    };
  });
}
// Recharger les disponibilités à chaque changement de domaine
watch(selectedSpecialty, () => {
  loadDomainAvailabilities();
});

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay',
  },
  events: [],
  editable: false,
  selectable: true,  // Permet la sélection des événements
  dayMaxEvents: true,
  weekends: true,
  eventClick: async function(info) {
    const event = info.event;
    const coachId = event.extendedProps.coach_id;

    if (event.extendedProps.statut === 'available') {
      try {
        // Préparation des paramètres pour la redirection
        const params = {
          coach_id: coachId,
          availability_id: Number(event.id),
        };

        // Redirection vers la page de réservation avec les paramètres dans l'URL
        await router.visit(`/startup/res/create?coach_id=${params.coach_id}&availability_id=${params.availability_id}`, {
          method: 'get',
          onSuccess: () => console.log('Redirection réussie'),
          onError: (errors) => {
            console.error('Erreurs:', errors);
            alert('Données invalides - Veuillez réessayer');
          },
        });
      } catch (error) {
        console.error('Erreur complète:', error);
      }
    }
  }
});
</script>

<template>
 
    <div>
  
      <div class="mb-3">
        <label for="specialtySelect" class="form-label">Sélectionner un domaine</label>
        <select
          id="specialtySelect"
          v-model="selectedSpecialty"
          class="form-select"
        >
          <option :value="null" disabled>Choisir un domaine</option>
          <option v-for="specialty in specialties" :key="specialty" :value="specialty">
            {{ specialty }}
          </option>
        </select>
      </div>

      <!-- Calendrier -->
      <div class="card-body" >
        <div class="row">
          <div class="col-md-12">
            <div id="right">
              <div class="card-header">
                <h2 class="card-title text-center mb-4">
                  Disponibilités des coachs du domaine : {{ selectedSpecialty || '...' }}
                </h2>
              </div>
              <div class="card-body">
                <FullCalendar :options="calendarOptions" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

</template>

<style scoped>
.calendar-container {
  
  margin: auto;
  /* padding: 20px; */
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
