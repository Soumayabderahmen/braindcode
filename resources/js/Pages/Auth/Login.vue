<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  canResetPassword: Boolean,
  status: String,
  message: { type: String, default: '' },
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onStart: () => (form.processing = true),
    onFinish: () => {
      form.reset('password')
      form.processing = false
    },
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />
    
    <div class="relative z-10">
      <!-- Floating elements animation in background -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div v-for="i in 6" :key="i" 
             class="floating-element" 
             :class="`element-${i}`">
        </div>
      </div>
      
      <div class="backdrop-blur-sm bg-white/70 rounded-xl p-8 shadow-xl border border-blue-50" style="margin: -5%;">
        <div class="mb-6 text-center">
          <h2 class="text-2xl font-bold text-primary relative inline-block">
            Connexion
            <span class="absolute -bottom-1 left-0 right-0 h-1 bg-gradient-to-r from-blue-300 to-indigo-500 transform scale-x-0 transition-transform duration-300 hover:scale-x-100"></span>
          </h2>
        </div>

        <!-- Message de succès -->
        <transition name="slide-fade">
          <div v-if="status" class="mb-6 flex items-center rounded-lg bg-green-100 p-4 text-sm font-medium text-green-600">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ status }}
          </div>
        </transition>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Email -->
          <div class="space-y-2 transform transition duration-300 hover:translate-x-1">
            <InputLabel for="email" value="Adresse e-mail" class="text-primary font-medium" />

            <div class="relative group">
              <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                  <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
              </div>
              <TextInput
                id="email"
                type="email"
                v-model="form.email"
                class="pl-10 w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                required
                autofocus
                placeholder="votre@email.com"
              />
              <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-gradient-to-r from-blue-400 to-indigo-600 transition-all duration-300 group-focus-within:w-full"></div>
            </div>
            <InputError class="text-red-500" :message="form.errors.email" />
          </div>

          <!-- Mot de passe -->
          <div class="space-y-2 transform transition duration-300 hover:translate-x-1">
            <div class="flex justify-between items-center">
              <InputLabel for="password" value="Mot de passe" class="text-primary font-medium" />
              <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors duration-200 hover:underline"
              >
                Mot de passe oublié ?
              </Link>
            </div>
            <div class="relative group">
              <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
              </div>
              <TextInput
                id="password"
                type="password"
                v-model="form.password"
                class="pl-10 w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                required
                placeholder="••••••••"
              />
              <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-gradient-to-r from-blue-400 to-indigo-600 transition-all duration-300 group-focus-within:w-full"></div>
            </div>
            <InputError class="text-red-500" :message="form.errors.password" />
          </div>

          <!-- Remember me -->
          <div class="flex items-center transform transition duration-300 hover:translate-x-1">
            <Checkbox v-model:checked="form.remember" class="text-indigo-600 rounded focus:ring-indigo-500" />
            <span class="ml-2 text-sm text-primary">Se souvenir de moi</span>
          </div>

          <!-- Bouton -->
          <div class="pt-2">
            <button
              type="submit"
              class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium py-3 px-4 rounded-lg transition duration-300 transform hover:translate-y-1 hover:shadow-lg focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:outline-none"
              :disabled="form.processing"
              :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
            >
              <span v-if="form.processing" class="inline-block animate-spin mr-2">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              {{ form.processing ? 'Connexion...' : 'Se connecter' }}
            </button>
          </div>

          <!-- Lien inscription -->
          <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
              Pas encore de compte ?
              <Link :href="route('register')" class="font-medium text-indigo-600 hover:text-indigo-800 transition-colors duration-200 hover:underline">
                Créer un compte
              </Link>
            </p>
          </div>
        </form>
      </div>
    </div>
  </GuestLayout>
</template>

<style scoped>
.text-primary {
  color: #005183;
}

/* Enhanced transitions */
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.4s ease;
}
.slide-fade-enter-from, .slide-fade-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}

/* Floating elements animation */
.floating-element {
  position: absolute;
  border-radius: 50%;
  opacity: 0.1;
  animation-name: float, pulse;
  animation-timing-function: ease-in-out;
  animation-iteration-count: infinite;
  animation-direction: alternate;
}

.element-1 {
  top: 10%;
  left: 10%;
  width: 100px;
  height: 100px;
  background: linear-gradient(45deg, #4f46e5, #3b82f6);
  animation-duration: 18s, 6s;
}

.element-2 {
  top: 60%;
  left: 5%;
  width: 70px;
  height: 70px;
  background: linear-gradient(45deg, #3b82f6, #2dd4bf);
  animation-duration: 22s, 7s;
}

.element-3 {
  top: 20%;
  right: 10%;
  width: 120px;
  height: 120px;
  background: linear-gradient(45deg, #6366f1, #a855f7);
  animation-duration: 25s, 8s;
}

.element-4 {
  bottom: 15%;
  right: 15%;
  width: 80px;
  height: 80px;
  background: linear-gradient(45deg, #ec4899, #f43f5e);
  animation-duration: 20s, 9s;
}

.element-5 {
  top: 40%;
  right: 30%;
  width: 60px;
  height: 60px;
  background: linear-gradient(45deg, #10b981, #14b8a6);
  animation-duration: 23s, 5s;
}

.element-6 {
  bottom: 30%;
  left: 30%;
  width: 90px;
  height: 90px;
  background: linear-gradient(45deg, #8b5cf6, #6366f1);
  animation-duration: 19s, 7s;
}

@keyframes float {
  0% { transform: translate(0, 0) rotate(0deg); }
  100% { transform: translate(30px, 30px) rotate(10deg); }
}

@keyframes pulse {
  0% { transform: scale(1); }
  100% { transform: scale(1.1); }
}
</style>