<template>
    
      <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
          
            <CompleterProfileForm :role="role" :userInfo="userInfo" />
          
  
   
            <UpdateProfileInformationForm
              :must-verify-email="mustVerifyEmail"
              :status="status"
              :user="userInfo"
              class="max-w-xl"
            />
          
  
          
            <UpdatePasswordForm class="max-w-xl" />
         
  
         
            <DeleteUserForm class="max-w-xl" />
     
        </div>
      </div>
 
  </template>
  
  <script setup>
  import { defineProps } from "vue";
  import DeleteUserForm from "./Partials/DeleteUserForm.vue";
  import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
  import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
  import CompleterProfileForm from "./Partials/CompleterProfileForm.vue";
  
  defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    userInfo: Object,
    role: String,
  });
  </script>
  