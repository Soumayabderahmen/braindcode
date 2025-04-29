<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import fullwidth from '@/Layouts/fullwidth.vue';
defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <fullwidth> <!-- 👈 ici aussi -->

        <Head title="Connexion" />

        <div class="container-xxl flex-grow-1 container-p-y auth-container" style="height:100vh;">
            <img src="/assets/img/dash/circletop.png" alt="" class="circle circletop">
            <img src="/assets/img/dash/circleright.png" alt="" class="circle circleright">
            <img src="/assets/img/dash/circletopright.png" alt="" class="circle circletopright">
            
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="card cardLogin">
                        <div class="card-body">
                            
                            <div v-if="Object.keys(form.errors).length > 0" class="alert alert-danger alert-dismissible" role="alert">
                                <ul>
                                    <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <div v-if="status" class="alert alert-success dark alert-dismissible fade show" role="alert">
                                <span>{{ status }}</span>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <form @submit.prevent="submit">
                                <h5 class="mb-5 title">Accéder à mon compte</h5>

                                <div class="input-group mb-4">
                                    <span class="input-group-text">
                                        <img src="/assets/img/icons/Mail-2.png" alt="">
                                    </span>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        placeholder="Exemple@gmail.com" 
                                        v-model="form.email" 
                                        required 
                                        autofocus
                                    >
                                </div>

                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        <img src="/assets/img/icons/lock.png" alt="">
                                    </span>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        placeholder="Mot de passe" 
                                        v-model="form.password" 
                                        required
                                    >
                                </div>

                                <div class="mb-4 d-flex justify-content-end">
                                    <Link v-if="canResetPassword" :href="route('password.request')">
                                        <small>Mot de passe oublié?</small>
                                    </Link>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mb-4" :disabled="form.processing">
                                    Se connecter
                                </button>
                            </form>

                            <div class="text-center mb-4">
                                <p class="text">
                                    Vous n’êtes pas encore client ? 
                                    <Link :href="route('register')">Créer un compte</Link>
                                </p>
                            </div>

                            <div class="d-flex align-items-center justify-content-center px-3 mb-4">
                                <div class="hr"></div>
                                <p>ou</p>
                                <div class="hr"></div>
                            </div>

                            <div class="card-footer bg-transp border-0 pb-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button class="btn btn-rect">
                                        <span class="iconify" data-icon="flowbite:google-solid" data-inline="false"></span>
                                    </button>
                                    <!--
                                    <button class="btn">
                                        <img src="/assets/img/icons/facebook.png" alt="">
                                    </button>
                                    -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </fullwidth> <!-- 👈 n'oublie de fermer ici -->
</template>
