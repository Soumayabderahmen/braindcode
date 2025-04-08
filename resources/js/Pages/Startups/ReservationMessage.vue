<script>
export default {
  name: 'ActivationMessage',
};
import Main from '../../Layouts/main.vue';

</script>
<template>
  <Main :showSidebar="true">

    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-100 to-purple-200">
      <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full text-center animate-fade-in">
        <!-- GIF animé -->
        <img src="/storage/startups/braindcode.png" alt="Chargement en attente" class="mx-auto w-20 h-20 mb-4" />

        <!-- Message principal -->
        <h2 class="text-2xl font-bold" style="color: #2e41ff96;">
          Votre Réservation est en attente d'acceptation
        </h2>
        <img src="/storage/startups/load.gif" alt="Chargement en attente" class="mx-auto w-20 h-20 mb-4" />

        <!-- Détails -->
        <p class="text-gray-700 mb-2">
          Le coach doit approuver votre réservation avant que vous puissiez payer.
        </p>
        <p class="text-gray-500 text-sm">
          Vous recevrez un email dès qu'elle sera acceptée.
        </p>

        <!-- Bouton -->
        <a href="/startup/calendar"
          class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 transition duration-300 text-white font-semibold px-6 py-2 rounded-full shadow-md">
          Retour au Calendrier 👉
        </a>
      </div>
    </div>
  </Main>

</template>



<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out;
}
</style>