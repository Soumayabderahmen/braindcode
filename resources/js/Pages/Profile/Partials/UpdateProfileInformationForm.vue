<template>
    <section>
      <header>
        <h2 class="text-lg font-medium text-gray-900">
          Profile Information
        </h2>
  
        <p class="mt-1 text-sm text-gray-600">
          Update your account's profile information and email address.
        </p>
      </header>
  
      <form @submit.prevent="submitForm" class="mt-6 space-y-6">
        <div>
          <InputLabel for="name" value="Name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>
  
        <div>
          <InputLabel for="email" value="Email" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>
  
        <div v-if="mustVerifyEmail && user.email_verified_at === null">
          <p class="mt-2 text-sm text-gray-800">
            Your email address is unverified.
            <a
              href="/email/verification-notification"
              class="underline text-sm text-gray-600 hover:text-gray-900"
            >
              Click here to re-send the verification email.
            </a>
          </p>
  
          <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
            A new verification link has been sent to your email address.
          </div>
        </div>
  
        <div class="flex items-center gap-4">
          <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
  
          <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
          >
            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
              Saved.
            </p>
          </Transition>
        </div>
      </form>
    </section>
  </template>
  
  <script setup>
  import { reactive } from 'vue';
  import axios from 'axios';
  
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';
  
  const { mustVerifyEmail, status, user } = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    user: Object,
  });
  
  const form = reactive({
    name: user.name,
    email: user.email,
    errors: {},
    processing: false,
    recentlySuccessful: false,
  });
  
  const submitForm = async () => {
    form.processing = true;
    form.errors = {};
    form.recentlySuccessful = false;
  
    try {
      await axios.patch('/profile/update', {
        name: form.name,
        email: form.email,
      });
      form.recentlySuccessful = true;
    } catch (error) {
      if (error.response?.data?.errors) {
        form.errors = error.response.data.errors;
      }
    } finally {
      form.processing = false;
    }
  };
  </script>
  