<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import Navbar_home from '@/Components/Navbar_home.vue'; 
import Footer from '@/Components/Footer.vue';
const form = useForm({
    name: '',
    email: '',
    category: 'general', // Valeur par défaut
    message: '',
    file: null,
});
const showSuccessPopup = ref(false)

const successMessage = ref('');

const categories = [
    { value: 'technical', label: 'Problème technique' },
    { value: 'general', label: 'Demande générale' },
    { value: 'other', label: 'Autre' }
];

const submit = () => {
  form.post(route('contact.store'), {
    onSuccess: () => {
      showSuccessPopup.value = true
      form.reset()
      setTimeout(() => {
        showSuccessPopup.value = false
      }, 3000)
    },
  })
}


const fileName = ref('');
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) { // 2MB max
            alert('Le fichier ne doit pas dépasser 2 Mo.');
            return;
        }
        fileName.value = file.name;
        form.file = file;
    }
};
</script>

<template>
    <!-- style="background-color: rgb(249 249 249);" -->
    <div class="min-h-screen " >
        <Navbar_home /> <!-- ✅ Navbar ajouté -->
        
        <div class="container mx-auto px-6 py-16">
            <div class="text-center mb-12">
               
                <h1 class="main-title">Contactez-nous</h1>
                <p class="text-gray-600 mt-2">
                    Besoin d'aide ? Remplissez le formulaire et notre équipe vous répondra rapidement.
                </p>
            </div>
            <transition name="fade">
  <div
    v-if="showSuccessPopup"
    class="fixed top-5 right-5 flex items-start gap-3 bg-green-100 text-green-800 border border-green-300 px-5 py-4 rounded-lg shadow-lg z-50"
  >
    <!-- Icône succès -->
    <svg class="w-6 h-6 mt-1 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>

    <!-- Message -->
    <div class="flex-1">
      <p class="font-medium">Succès !</p>
      <p class="text-sm">Votre message a été envoyé avec succès.</p>
    </div>

    <!-- Bouton de fermeture -->
    <button
      @click="showSuccessPopup = false"
      class="text-green-500 hover:text-green-700 transition"
      aria-label="Fermer"
    >
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path
          fill-rule="evenodd"
          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
          clip-rule="evenodd"
        />
      </svg>
    </button>
  </div>
</transition>
            <div class="flex flex-col lg:flex-row gap-10 items-center justify-between">
                <!-- Formulaire -->
                <div data-v-f5b5fb07="" class="md:col-span-2 bg-white shadow-lg rounded-lg p-8 w-full lg:w-2/3" style="width: 83%;background-color: rgb(249 249 249);">
                    <h2 class="text-2xl font-semibold mb-6 text-center">Envoyez-nous un message</h2>

                   
                    
                    <form @submit.prevent="submit">
                        <!-- Nom & Email -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium">Nom</label>
                                <input v-model="form.name" type="text" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-300" required />
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium">Email</label>
                                <input v-model="form.email" type="email" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-300" required />
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div class="mt-4">
                            <label class="block text-gray-700 font-medium">Catégorie</label>
                            <select v-model="form.category" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-300">
                                <option v-for="option in categories" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div class="mt-4">
                            <label class="block text-gray-700 font-medium">Message</label>
                            <textarea v-model="form.message" rows="4" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-indigo-300" required></textarea>
                        </div>

                        <!-- Fichier -->
                        <div class="mt-4">
                            <label class="block text-gray-700 font-medium">Joindre un fichier (optionnel)</label>
                            <input type="file" @change="handleFileChange" class="w-full px-4 py-2 border rounded-md" />
                            <p v-if="fileName" class="text-sm text-gray-500 mt-1">Fichier sélectionné : {{ fileName }}</p>
                        </div>

                        <button data-v-f5b5fb07="" type="submit" class="w-full text-white px-4 py-3 rounded-md hover:bg-indigo-700 transition duration-300 mt-4" style="background-color: #00AEEF;"> Envoyer </button>
                    </form>
                </div>

                <!-- Infos Contact -->
                <div class="space-y-6" style="margin-bottom: 2%;">
                    <!-- <div class="flex items-center space-x-4 bg-white shadow-lg p-6 rounded-lg" >
                        <i class="fa fa-map-marker text-indigo-600 text-3xl" ></i>
                        <div>
                            <h4 class="text-lg font-semibold" >Notre Adresse</h4>
                            <p class="text-gray-600">3481 Melrose Place, Beverly Hills</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 bg-white shadow-lg p-6 rounded-lg">
                        <i class="fa fa-envelope text-indigo-600 text-3xl"></i>
                        <div>
                            <h4 class="text-lg font-semibold">Email</h4>
                            <p class="text-blue-600-600">support@braindcode.com</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 bg-white shadow-lg p-6 rounded-lg">
                        <i class="fa fa-phone text-indigo-600 text-3xl"></i>
                        <div>
                            <h4 class="text-lg font-semibold">Téléphone</h4>
                            <p class="text-gray-600">(+33) 517 397 7100</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 bg-white shadow-lg p-6 rounded-lg">
                        <i class="fa fa-clock text-indigo-600 text-3xl"></i>
                        <div>
                            <h4 class="text-lg font-semibold">Horaires</h4>
                            <p class="text-gray-600">Lun - Ven: 08:00 - 16:00</p>
                            <p class="text-gray-600">Sam: 10:00 - 14:00</p>
                        </div>
                    </div> -->
                    <div>
                <div class="help-bubble" ></div>
                <img src="/images/support-person.png" alt="Support" />
            </div>
<!-- Contact rapide sous l’image -->
<div class="mt-10 flex flex-col sm:flex-row gap-6 items-start">
  <!-- Téléphone -->
  <div class="flex items-center gap-4 bg-white shadow-md rounded-lg p-5 w-full sm:w-auto">
    <i class="fab fa-whatsapp text-2xl text-[#00AEEF]"></i>
    <span class="font-medium text-sm text-[#333]">+33 99532366</span>
  </div>

  <!-- Email -->
  <div class="flex items-center gap-4 bg-white shadow-md rounded-lg p-5 w-full sm:w-auto">
    <i class="fas fa-envelope text-2xl text-[#00AEEF]"></i>
    <span class="font-medium text-sm text-[#333]">braindcode@gmail.com</span>
  </div>
</div>
 
                </div>


            </div>


        </div>

    </div>

           

    <Footer />
    <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>
</template>

<style scoped>

@keyframes slide-in {
  0% {
    opacity: 0;
    transform: translateX(100%);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-slide-in {
  animation: slide-in 0.5s ease-out;
}

input, textarea, select {
    border: 1px solid #ddd;
}
.main-title {
font-size: 2.5rem;
  font-weight: 800;
 
  margin-bottom: 0.2rem;
color: var(--Blue-blue-700, #253d4d);

font-family: 'Poppins', sans-serif;
font-size: 49px;
font-style: bold;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
