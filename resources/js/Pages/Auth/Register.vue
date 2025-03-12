<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { watch } from 'vue';

const form = useForm({
    name: '',  // Nom de Startup ou d'Investisseur
    email: '',
    password: '',
    password_confirmation: '',
    role: 'startup',  // Startup est le rôle par défaut
    visibility: '',
    image: null,
    domain_name: '',  // Domaine de la Startup
    specialty: '' ,
    document: null,
    statut: 'active', 
});

const onRoleChange = (role) => {
    console.log("Rôle sélectionné :", role);
    form.role = role; 
    form.statut = (role === 'coach') ? 'inactive' : 'active';

    // Mettez à jour form.role explicitement si nécessaire
    // Si le rôle est "investisseur", effacer le champ name et définir investor_name
    if (role === 'investisseur') {
        form.name = '';  // On vide le champ name pour les investisseurs
    } 


};
watch(() => form.role, (newRole) => {
    form.statut = (newRole === 'coach') ? 'inactive' : 'active';
});
const submit = () => {
    if (form.role !== 'coach') {
        form.document = null; // Supprimer 'document' si ce n'est pas un coach
    }
    console.log('Envoi du statut:', form.statut);

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />
       
        <form @submit.prevent="submit">
            <div class="mt-4">
                <InputLabel value="Choisissez votre rôle" />
                <div class="flex space-x-4 mt-2">
                    <label class="radio-label">
                        <input 
                            type="radio" 
                            name="role" 
                            value="startup" 
                            v-model="form.role" 
                            @change="onRoleChange('startup')" />
                        Startup
                    </label>
                    <label class="radio-label">
                        <input 
                            type="radio" 
                            name="role" 
                            value="coach" 
                            v-model="form.role" 
                            @change="onRoleChange('coach')" />
                        Coach
                    </label>
                    <label class="radio-label">
                        <input 
                            type="radio" 
                            name="role" 
                            value="investisseur" 
                            v-model="form.role" 
                            @change="onRoleChange('investisseur')" />
                        Investisseur
                    </label>
                </div>
                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <div class="mt-4">

            <!-- Champ pour le nom ou le nom de startup -->
            <div v-if="form.role == 'coach'">
                <InputLabel for="name" value="Nom du Coach" />
                <TextInput
                    id="coach_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div v-if="form.role == 'startup'">
                <InputLabel for="name" value="Nom de Startup" />
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
            <!-- Champ pour le nom de l'investisseur -->
            <div v-if="form.role === 'investisseur'">
                <InputLabel for="name" value="Nom de l'Investisseur" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                />
                <InputError class="mt-2" :message="form.errors.investor_name" />
            </div>
</div>
            <!-- Champ pour l'email -->
            <div class="mt-4">
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


            <!-- Champ pour le mot de passe -->
            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Champ pour la confirmation du mot de passe -->
            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <!-- Champs spécifiques selon le rôle -->
            <div v-if="form.role === 'investisseur'" class="mt-4">
                <InputLabel for="visibility" value="Visibilité" />
                <select v-model="form.visibility" class="mt-1 block w-full">
                    <option value="public">Public</option>
                    <option value="private">Privée</option>
                </select>
                <InputError class="mt-2" :message="form.errors.visibility" />

                <InputLabel for="image" value="Image" />
                <input type="file" id="image" @change="e => form.image = e.target.files[0]" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.image" />
            </div>

            <div v-if="form.role === 'startup'" class="mt-4">
                <InputLabel for="domain_name" value="Nom de Domaine" />
                <TextInput
                    id="domain_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.domain_name"
                />
                <InputError class="mt-2" :message="form.errors.domain_name" />
            </div>

            <div v-if="form.role === 'coach'" class="mt-4">
                <InputLabel for="specialty" value="Spécialité" />
                <TextInput
                    id="specialty"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.specialty"
                />
                <InputError class="mt-2" :message="form.errors.specialty" />
                <InputLabel for="document" value="Document justificatif (PDF)" />
    <input 
        type="file" 
        id="document" 
        accept="application/pdf" 
       @change="e => form.document = e.target.files[0]"
        class="mt-1 block w-full" 
    />
    <InputError class="mt-2" :message="form.errors.document" />
            </div>

            <!-- Bouton de soumission -->
            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
.radio-label {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.radio-label input {
    margin-right: 8px;
}
</style>