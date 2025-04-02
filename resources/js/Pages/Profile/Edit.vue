<template>
    <Main :showSidebar="true">
      <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
          <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
            <CompleterProfileForm :role="role" :userInfo="userInfo" />
          </div>
  
          <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
            <UpdateProfileInformationForm
              :must-verify-email="mustVerifyEmail"
              :status="status"
              class="max-w-xl"
            />
          </div>
  
          <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
            <UpdatePasswordForm class="max-w-xl" />
          </div>
  
          <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
            <DeleteUserForm class="max-w-xl" />
          </div>
        </div>
      </div>
    </Main>
  </template>
  
  <script setup>
  import { defineProps } from "vue";
  import Main from "../../Layouts/main.vue";
  import DeleteUserForm from "./Partials/DeleteUserForm.vue";
  import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
  import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
  import CompleterProfileForm from "./Partials/CompleterProfileForm.vue";
  import { Head } from "@inertiajs/vue3";
  
  defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    userInfo: Object,
    role: String,
  });
  </script>
  