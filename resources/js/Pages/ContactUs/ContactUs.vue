<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Navbar_home from '@/Components/Navbar_home.vue'; 
import Footer from '@/Components/Footer.vue';
const form = useForm({
    name: '',
    email: '',
    category: 'general', // Valeur par défaut
    message: '',
    file: null,
});

const successMessage = ref('');

const categories = [
    { value: 'technical', label: 'Problème technique' },
    { value: 'general', label: 'Demande générale' },
    { value: 'other', label: 'Autre' }
];

const submit = () => {
    form.post(route('contact.store'), {
        onSuccess: () => {
            successMessage.value = 'Votre message a été envoyé avec succès !';
            form.reset();
        },
    });
};

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
    <div class="min-h-screen bg-gray-100">
        <Navbar_home /> <!-- ✅ Navbar ajouté -->

        <div class="container mx-auto px-6 py-16">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900">Contactez-nous</h1>
                <p class="text-gray-600 mt-2">
                    Besoin d'aide ? Remplissez le formulaire et notre équipe vous répondra rapidement.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Formulaire -->
                <div class="md:col-span-2 bg-white shadow-lg rounded-lg p-8">
                    <h2 class="text-2xl font-semibold mb-6">Envoyez-nous un message</h2>

                    <p v-if="successMessage" class="text-green-600 text-center mb-4">
                        {{ successMessage }}
                    </p>

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

                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-md hover:bg-indigo-700 transition duration-300 mt-4">
                            Envoyer
                        </button>
                    </form>
                </div>

                <!-- Infos Contact -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 bg-white shadow-lg p-6 rounded-lg" >
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>

<style scoped>
input, textarea, select {
    border: 1px solid #ddd;
}
</style>
