<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Main from '../../Layouts/main.vue';
import 'bootstrap/dist/css/bootstrap.min.css';

const props = defineProps({
  coach: Object,
  availability: Object,
  date: String,
  slots: Array, 
  honoraire: Number,
  startup_id: Number,
});

const form = ref({
  coach: props.coach?.user?.name || '',
  availability_id: props.availability.id,
  startup_id: props.startup_id,

  message: '',
  selected_time: '',
  duration: 30, // durée par défaut
  honoraire: props.honoraire,
  total:0,
});


// Convertir HH:mm en minutes
const timeToMinutes = (time) => {
  const [h, m] = time.split(':').map(Number);
  return h * 60 + m;
};

// Convertir les minutes en HH:mm
const minutesToTime = (totalMinutes) => {
  const hours = Math.floor(totalMinutes / 60).toString().padStart(2, '0');
  const minutes = (totalMinutes % 60).toString().padStart(2, '0');
  return `${hours}:${minutes}`;
};

// Créneaux filtrés dynamiquement
const filteredSlots = computed(() => {
  const duration = parseInt(form.value.duration);
  const times = props.slots;

  const result = [];

  for (let i = 0; i < times.length; i++) {
    const startMinutes = timeToMinutes(times[i]);
    const endTime = minutesToTime(startMinutes + duration);

    // Vérifie que l'heure de fin existe dans les slots OU est inférieure au dernier slot
    if (timeToMinutes(endTime) <= timeToMinutes(times[times.length - 1]) + 30) {
      result.push({
        start: times[i],
        end: endTime,
        label: `${times[i]} - ${endTime}`,
      });
    }
  }

  return result;
});
watch(() => form.value.duration, (newDuration) => {
  const horaire = parseFloat(form.value.honoraire);
  form.value.total = ((horaire * newDuration) / 60).toFixed(2); // .toFixed pour afficher 2 décimales
});
const submitReservation = () => {
  form.value.message = form.value.message?.trim() || '';

  router.post('/startup/reservation/add', form.value, {
    onSuccess: () => {
      alert('✅ Réservation enregistrée avec succès.');
    },
    onError: (errors) => {
      console.error('❌ Erreurs de validation :', errors);
      alert('Une erreur est survenue lors de la réservation.');
    },
  });
};

</script>

<template>
  <Main :showSidebar="true">
    <div>
      <h1> 📍Réservation avec {{ coach?.user?.name || 'Coach non spécifié' }} 📍</h1>

      <form @submit.prevent="submitReservation">
        <div class="form-group">
          <label>Nom du Coach</label>
          <input type="text" v-model="form.coach" class="form-control" disabled />
        </div>

        <div class="form-group">
          <label>Durée de la réunion (minutes)</label>
          <input type="number" v-model="form.duration" class="form-control" min="15" step="15" />
        </div>

        <div class="form-group">
          <label>Choisir une heure</label>
          <select v-model="form.selected_time" class="form-control" required>
            <option disabled value="">Sélectionner une heure</option>
            <option
              v-for="slot in filteredSlots"
              :key="slot.start"
              :value="slot.start"
            >
              {{ slot.label }}
            </option>
          </select>
        </div>
        <div class="form-group">
  <label>Honoraire par heure (€)</label>
  <input type="text" v-model="form.honoraire" class="form-control" disabled />
</div>

<div class="form-group">
  <label>Total (€)</label>
  <input type="text" v-model="form.total" class="form-control" disabled />
</div>
        <div class="form-group">
          <label>Message</label>
          <textarea v-model="form.message" class="form-control" placeholder="Ajouter un message" />
        </div>

        <button type="submit" class="btn btn-primary mt-3">Réserver</button>
      </form>
    </div>
  </Main>
</template>

<style scoped>
</style>
