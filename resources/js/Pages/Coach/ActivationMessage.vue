<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
      <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-2xl font-bold text-red-600 mb-4">Votre compte est en attente d'activation</h2>
        <p class="text-gray-700">Un administrateur doit approuver votre inscription avant que vous puissiez accéder à la plateforme.</p>
        <p class="text-gray-500 mt-2">Vous recevrez un email une fois votre compte activé.</p>
        <a href="/login" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
    Retour à l'accueil
</a>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ActivationMessage'
  };
  </script>
  