<template>
    <div class="max-w-xl mx-auto py-10">
      <h2 class="text-2xl font-bold mb-6">Paiement pour la réservation</h2>
      <p class="mb-2"><strong>Réservé par :</strong> {{ reservation.startup.user.name }}</p>
      <p class="mb-2"><strong>Date :</strong> {{ formatDate(reservation.meeting_time) }}</p>
      <p class="mb-2"><strong>Montant :</strong> {{ reservation.total }} €</p>
  
      <div v-if="clientSecret">
        <StripeElements :client-secret="clientSecret">
          <PaymentElement />
          <button
            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            @click="handleSubmit"
            :disabled="loading"
          >
            {{ loading ? 'Paiement en cours...' : 'Payer maintenant' }}
          </button>
        </StripeElements>
      </div>
  
      <div v-else class="text-gray-600">Chargement du paiement...</div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { usePage } from '@inertiajs/vue3'
  import axios from 'axios'
  import {
    StripeElements,
    PaymentElement,
    useStripe,
    useElements
  } from '@stripe/vue-stripe-js'
  
  const page = usePage()
  const reservation = page.props.reservation
  const user = page.props.user
  
  const clientSecret = ref(null)
  const loading = ref(false)
  
  const stripe = useStripe()
  const elements = useElements()
  
  const handleSubmit = async () => {
    loading.value = true
    const { error } = await stripe.value.confirmPayment({
      elements: elements.value,
      confirmParams: {
        return_url: window.location.origin + '/dashboard', // Redirection après paiement
      },
    })
  
    if (error) {
      alert(error.message)
    }
    loading.value = false
  }
  
  onMounted(async () => {
    const response = await axios.post('/paiement/create-intent', {
      userId: user.id,
      reservation_id: reservation.id,
      amount: reservation.total * 100,
    })
    clientSecret.value = response.data.clientSecret
  })
  </script>
  
  <style scoped>
  </style>
  