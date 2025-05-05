<script setup>
import { ref, computed, watch } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

const props = defineProps({
  availabilities: Array,
  coachs: Array,
});

const selectedSpecialty = ref(null);

const specialties = computed(() => {
  return [...new Set(props.coachs.map(coach => coach.specialty))];
});

const filteredCoachs = computed(() => {
  if (!selectedSpecialty.value) return [];
  return props.coachs.filter(coach => coach.specialty === selectedSpecialty.value);
});

function loadDomainAvailabilities() {
  if (!selectedSpecialty.value) {
    calendarOptions.value.events = [];
    return;
  }

  const coachIds = filteredCoachs.value.map(coach => coach.coach_id);
  const domainAvailabilities = props.availabilities.filter(
    (avail) => coachIds.includes(avail.coach_id) && avail.statut === 'available'
  );

  const avatars = ['1.png', '2.png', '3.png', '4.png'];

  calendarOptions.value.events = domainAvailabilities.map((avail) => {
    const coach = props.coachs.find(c => c.coach_id === avail.coach_id);
    const randomAvatar = avatars[Math.floor(Math.random() * avatars.length)];

    return {
      id: avail.id,
      title: `Coaching Stratégique`,
      start: `${avail.date}T${avail.start_time}`,
      end: `${avail.date}T${avail.end_time}`,
      color: '#3399ff',
      extendedProps: {
        coach_id: avail.coach_id,
        statut: avail.statut,
        formateur: coach?.name || 'Inconnu',
        avatar: randomAvatar,
        mode: 'Formation en ligne'
      }
    };
  });
}

watch(selectedSpecialty, () => {
  loadDomainAvailabilities();
});

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, bootstrap5Plugin],
  themeSystem: 'bootstrap5',
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay',
  },
  events: [],
  eventDidMount(info) {
    const avatar = info.event.extendedProps.avatar ?? 'default.jpg';
    const formateur = info.event.extendedProps.formateur ?? 'Inconnu';
    const date = new Date(info.event.start).toLocaleDateString('fr-FR');
    const heure = new Date(info.event.start).toLocaleTimeString('fr-FR', {
      hour: '2-digit',
      minute: '2-digit'
    });
    const fin=new Date(info.event.end).toLocaleTimeString('fr-FR', {
      hour: '2-digit',
      minute: '2-digit'
    });
    const mode = info.event.extendedProps.mode ?? 'Formation en ligne';
const statut = info.event.extendedProps.statut ?? 'Indisponible';
    tippy(info.el, {
      allowHTML: true,
      animation: 'scale',
      theme: 'light',
      interactive: true,
      content: `
        <div style="padding:10px; width: 198px;">
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;width: 100%;">
            <img src="/assets/img/avatars/${avatar}" alt="avatar" style="width:40px; height:40px; border-radius:50%;">
            <strong>${formateur}</strong>
          </div>
          <div>📍 ${info.event.title}</div>
          <div>📅 ${date}</div>
          <div>⏰ ${heure} -${fin}</div>
          <div>🧑‍💻 ${mode}</div>
          <div>🟢 ${statut}</div>
        </div>
      `
    });
  },
  editable: false,
  selectable: true,
  dayMaxEvents: true,
  weekends: true,
  eventClick(info) {
    const event = info.event;
    const coachId = event.extendedProps.coach_id;

    if (event.extendedProps.statut === 'available') {
      const params = new URLSearchParams({
        coach_id: coachId,
        availability_id: event.id
      }).toString();

      window.location.href = `/startup/res/create?${params}`;
    }
  }
});
</script>

<template>
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

  <div>
    <div class="mb-3">
      <label for="specialtySelect" class="form-label"><b>Sélectionner un domaine</b></label>
      <select id="specialtySelect" v-model="selectedSpecialty" class="form-select mb-4 me-8" style="
    border-radius: 15px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    padding: 13px 35px;
  
">
        <option :value="null" disabled>Choisir un domaine</option>
        <option v-for="specialty in specialties" :key="specialty" :value="specialty">
          {{ specialty }}
        </option>
      </select>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div id="right">
            <div class="card-header">
              <h2 class="card-title text-center mb-4">
                <b>Disponibilités des coachs du domaine : {{ selectedSpecialty || '...' }}</b>
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
:deep(.fc) {
  font-family: 'Inter', sans-serif;
  background-color: #eef6fd;
  padding: 1rem;
  border-radius: 20px;
}

:deep(.fc-toolbar) {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

:deep(.fc-button) {
  background-color: #e3f0fc !important;
  color: #0066cc !important;
  border: none !important;
  padding: 8px 20px;
  border-radius: 25px !important;
  font-weight: 500;
  text-transform: none;
}

:deep(.fc-button-active) {
  background-color: #0066cc !important;
  color: #fff !important;
}

:deep(.fc-daygrid-day-frame) {
  background-color: white;
  border-radius: 30px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  padding: 0.5rem;
  height: 100%;
  transition: all 0.2s ease-in-out;
  height: 100px;
  width: 140px;
}

:deep(.fc .fc-daygrid-body tr) {
  height: 110px !important;
}

:deep(.fc .fc-daygrid-day-frame) {
  margin-top: 8px !important;
  margin-bottom: 8px !important;
}

:deep(.fc .fc-daygrid-day) {
  padding: 8px;
  border-radius: 16px;
}

:deep(.fc-daygrid-day-number) {
  font-weight: bold;
  color:#3399ff;
}

:deep(.fc-daygrid-event) {
  background-color: #3399ff !important;
  color: white !important;
  font-size: 12px;
  border-radius: 8px;
  padding: 3px 5px;
  white-space: normal;
  word-break: break-word;
  margin-top: 3px;
}

.tippy-box[data-theme~='light'] {
  background-color: white;
  border: 1px solid #eee;
  box-shadow: 0 2px 8px #3399ff;
  border-radius: 12px;
  padding: 0;
  color: #3399ff;
  width: 100px;
  font-size: 13px;
}

</style>
