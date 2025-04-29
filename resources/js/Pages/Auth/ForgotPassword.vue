<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import fullwidth from '@/Layouts/fullwidth.vue';
defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>


<template>
  <fullwidth>
    <Head title="Mot de passe oublié" /> <!-- Titre de la page -->

    <!-- Section de description -->
   

    <!-- Message de statut, si disponible -->
    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <!-- Formulaire de réinitialisation -->
    <div class="container-xxl flex-grow-1 container-p-y auth-container d-flex justify-content-center align-items-center" style="height:100vh;">
      <img src="{{ asset('assets/img/dash/circletop.png') }}" alt="" class="circle circletop">
      <img src="{{ asset('assets/img/dash/circleright.png') }}" alt="" class="circle circleright">
      <img src="{{ asset('assets/img/dash/circletopright.png') }}" alt="" class="circle circletopright">
      <div class="row d-flex justify-content-center align-items-center w-100">
        <div class="col-lg-5 col-md-6 col-12">
          <div class="card cardLogin">
            <div class="card-body">
              <form @submit.prevent="submit">
                <div v-if="form.errors.email">
                  <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ form.errors.email }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>

                <h5 class="mb-5 title">Mot de passe oublié</h5>

                <!-- Email Input -->
                <div class="input-group mb-4">
                  <span class="input-group-text">
                    <img src="{{ asset('assets/img/icons/Mail-2.png') }}" alt="">
                  </span>
                  <TextInput
                    id="email"
                    type="email"
                    class="form-control"
                    placeholder="Exemple@gmail.com"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                  />
                  <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Submit Button -->
                <button class="btn btn-primary w-100 mb-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                  Envoyer le lien de réinitialisation
                </button>
              </form>

              <!-- Lien vers la création de compte -->
              <div class="text-center mb-4">
                <p class="text">Vous n’êtes pas encore client ? <a href="{{ route('admin.register') }}">Créer un compte</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </fullwidth>
</template>
