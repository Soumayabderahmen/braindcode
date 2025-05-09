<script setup>
import { defineProps, reactive } from 'vue';
import axios from 'axios';

defineProps({
    role: {
        type: String,
    },
    userInfo: {
        type: Object,
    },
});

// Créer un objet réactif pour stocker les données du formulaire
const form = reactive({
    diploma: null,
    competence: '',
    description: '',
    profile_image: null,
    cover_image: null,
    pdf_document: null,
    video_presentation: '',
    website_link: '',
    social_links: '',
    logo_startup:null,
    adresse:'',
    NameCo_fondateur:'',
    errors :{},
});

// Fonction pour gérer la soumission du formulaire
const saveProfile = async () => {
    try {
        let formData = new FormData();
        Object.keys(form).forEach(key => {
            formData.append(key, form[key]);
        });

        const response = await axios.post('/profile/update', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        alert('Profile updated successfully!');
        console.log(response.data);
    } catch (error) {
        console.error('Error updating profile:', error);
        alert('An error occurred while updating the profile.');
    }
};

const handleVideoUpload = (event) => {
    const file = event.target.files[0];
    const validTypes = ['video/mp4', 'video/avi', 'video/quicktime', 'video/x-matroska'];  // Ajout de 'video/x-matroska' pour .mkv
    if (!validTypes.includes(file.type)) {
        alert('Please upload a valid video file (mp4, avi, mov, mkv).');
        return;
    }
    form.video_presentation = file;
};
const handleCoverImageUpload = (event) => {
    const file = event.target.files[0];
    const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];  // Liste des types d'image valides

    if (!validImageTypes.includes(file.type)) {
        alert('Please upload a valid image file (jpeg, png, jpg, gif, webp).');
        return;
    }

    form.cover_image = file;
};
const handleProfileImageUpload = (event) => {
    const file = event.target.files[0];
    const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];  // Types d'images valides

    if (!validImageTypes.includes(file.type)) {
        alert('Please upload a valid image file (jpeg, png, jpg, gif, webp).');
        return;
    }

    form.profile_image = file;
};

const handleLogoStartupUpload = (event) => {
    const file = event.target.files[0];
    const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];  // Types d'images valides

    if (!validImageTypes.includes(file.type)) {
        alert('Please upload a valid image file (jpeg, png, jpg, gif, webp).');
        return;
    }

    form.logo_startup = file;
};
</script>

<template>
    <div class="mt-3  formulaire">
        <div class="col-12">
            <div class="card card-1">
                <div class="card-body px-lg-4 px-md-3 px-2">
                    <div class="bs-stepper wizard-numbered shadow-none mt-2">

        <form @submit.prevent="saveProfile">
            <!-- Coach Fields -->
            <div v-if="role === 'coach'">
                

                <label for="diploma" value="diploma justificatif (PDF)">Diploma</label>
    <input 
        type="file" 
        id="diploma" 
        accept="application/pdf" 
       @change="e => form.diploma = e.target.files[0]"
        class="form-control " 
    />
    <InputError class="mt-2" :message="form.errors.diploma" />
                <label for="competence">Competence</label>
                <textarea id="competence" v-model="form.competence"  class="form-control " ></textarea>

                <label for="description">Description</label>
                <textarea id="description" v-model="form.description"  class="form-control " ></textarea>

                <label for="profile_image">Cover Image</label>
                <input type="file" id="cover_image" @change="handleCoverImageUpload"  class="form-control "  />
                <label for="profile_image">profil Image</label>
                <input type="file" id="profile_image" @change="handleProfileImageUpload"  class="form-control "  />
            </div>
            <div v-if="role === 'startup'">
                <label for="logo_startup">Logo Startup</label>
                <input type="file" id="logo_startup" @change="handleLogoStartupUpload" class="form-control "  />
                <label for="adresse">Adresse</label>
                <textarea id="adresse" v-model="form.adresse"  class="form-control " ></textarea>
                <label for="NameCo_fondateur">Nom du Co_Fondateur</label>
                <input id="NameCo_fondateur" v-model="form.NameCo_fondateur"  class="form-control " ></input>
    
            </div>
            <!-- Investisseur Fields -->
            <div v-if="role === 'investisseur'">
                <label for="video_presentation">Video Presentation</label>
                <input type="file" id="video_presentation" @change="handleVideoUpload"  class="form-control "  />

                <label for="description">Description</label>
                <textarea id="description" v-model="form.description"  class="form-control " ></textarea>

                <label for="website_link">Website Link</label>
                <input type="url" id="website_link" v-model="form.website_link"  class="form-control "  />

                <label for="social_links">Social Links (JSON)</label>
                <textarea id="social_links" v-model="form.social_links"  class="form-control " ></textarea>

                <label for="profile_image">Cover Image</label>
                <input type="file" id="cover_image" @change="handleCoverImageUpload"  class="form-control "  />
                <label for="profile_image">profil Image</label>
                <input type="file" id="profile_image" @change="handleProfileImageUpload"  class="form-control " />
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    </div></div></div></div>
</template>
