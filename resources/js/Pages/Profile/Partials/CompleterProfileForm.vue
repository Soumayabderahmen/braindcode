<script setup>
import { defineProps } from 'vue';

defineProps({
    role: {
        type: String,
    },
    userInfo: {
        type: Object,
    },
});
</script>

<template>
    <div>
        <h3 class="text-xl font-semibold">Complete your Profile</h3>

        <!-- Formulaire général pour tous les utilisateurs -->
        <form method="POST" action="#">
            

            <!-- Champ spécifique au coach -->
            <div v-if="role === 'coach'">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" v-model="userInfo.phone_number" class="mt-1 block w-full" />
                
                <label for="diploma">Diploma</label>
                <input type="text" id="diploma" v-model="userInfo.diploma" class="mt-1 block w-full" />

                <label for="competence">Competence</label>
                <textarea id="competence" v-model="userInfo.competence" class="mt-1 block w-full"></textarea>

                <label for="description">Description</label>
                <textarea id="description" v-model="userInfo.description" class="mt-1 block w-full"></textarea>

                <label for="profile_image">Profile Image</label>
                <input type="file" id="profile_image" class="mt-1 block w-full" />
            </div>

            <!-- Champ spécifique à l'investisseur -->
            <div v-if="role === 'investisseur'">
                <label for="video_presentation">Video Presentation</label>
                <input type="file" id="video_presentation" class="mt-1 block w-full" />
                
                <label for="description">Description</label>
                <textarea id="description" v-model="userInfo.description" class="mt-1 block w-full"></textarea>

                <label for="website_link">Website Link</label>
                <input type="url" id="website_link" v-model="userInfo.website_link" class="mt-1 block w-full" />

                <label for="social_links">Social Links (JSON)</label>
                <textarea id="social_links" v-model="userInfo.social_links" class="mt-1 block w-full"></textarea>

                <label for="profile_image">Profile Image</label>
                <input type="file" id="profile_image" class="mt-1 block w-full" />
            </div>

            <!-- Ajouter d'autres champs selon les besoins -->
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</template>
