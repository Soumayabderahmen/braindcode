<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const form = ref({
  name: '',
  email: '',
  category: 'general',
  message: '',
  file: null
});


const showSuccessPopup = ref(false);
const fileName = ref('');
const isFormVisible = ref(false);
const isInfoVisible = ref(false);

const categories = [
    { value: 'technical', label: 'Problème technique' },
    { value: 'general', label: 'Demande générale' },
    { value: 'other', label: 'Autre' }
];

onMounted(() => {
    // Déclencher les animations après le chargement du composant
    setTimeout(() => {
        isFormVisible.value = true;
        setTimeout(() => {
            isInfoVisible.value = true;
        }, 300);
    }, 300);
});

const submit = async () => {
  const formData = new FormData();
  formData.append('name', form.value.name);
  formData.append('email', form.value.email);
  formData.append('category', form.value.category);
  formData.append('message', form.value.message);
  if (form.value.file) {
    formData.append('file', form.value.file);
  }

  try {
    const response = await axios.post('/contact/store', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    // Reset form + afficher popup
    showSuccessPopup.value = true;
    form.value.name = '';
    form.value.email = '';
    form.value.category = 'general';
    form.value.message = '';
    form.value.file = null;
    fileName.value = '';

    createConfetti();

    setTimeout(() => {
      showSuccessPopup.value = false;
    }, 5000);
  } catch (error) {
    console.error(error);
    alert("Une erreur est survenue. Veuillez réessayer.");
  }
};


const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file && file.size <= 2 * 1024 * 1024) {
    fileName.value = file.name;
    form.value.file = file;
  } else {
    alert("Fichier trop grand (max 2 Mo)");
    event.target.value = '';
  }
};


// Animation confetti pour le succès
const createConfetti = () => {
    const confettiCount = 50;
    const container = document.querySelector('.confetti-container');
    
    if (container) {
        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            
            // Couleurs aléatoires
            const colors = ['#00AEEF', '#FFC107', '#FF5722', '#4CAF50', '#9C27B0'];
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            
            // Position et taille aléatoires
            confetti.style.left = Math.random() * 60 + 20 + 'vw'; 
            confetti.style.width = Math.random() * 10 + 5 + 'px';
            confetti.style.height = Math.random() * 10 + 5 + 'px';
            confetti.style.opacity = '0.7';

            // Animation aléatoire
            confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
            confetti.style.animationDelay = Math.random() * 5 + 's';
            
            container.appendChild(confetti);
            
            // Supprimer après l'animation
            setTimeout(() => {
                confetti.remove();
            }, 8000);
        }
    }
};

// Fonction pour animer les champs quand on clique dessus
const focusField = (event) => {
    event.target.classList.add('field-focused');
};

// Vérifier si le champ est rempli
const checkField = (fieldName) => {
    return form[fieldName] && form[fieldName].length > 0 ? 'field-filled' : '';
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-sky-50 to-blue-50 relative overflow-hidden">
        <!-- Confetti container -->
        <div class="confetti-container fixed inset-0 pointer-events-none z-50"></div>
        
        <!-- Floating shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 py-16 relative z-10">
            <div class="text-center mb-12 animate-fade-in-down">
                <h1 class="main-title relative inline-block">
                    Contactez-nous
                    <span class="title-underline"></span>
                </h1>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto text-lg">
                    Besoin d'aide ? Remplissez le formulaire et notre équipe vous répondra rapidement.
                </p>
            </div>
            
            <!-- Success Popup -->
            <transition name="slide-down">
                <div
                    v-if="showSuccessPopup"
                    class="fixed top-5 left-1/2 transform -translate-x-1/2 flex items-start gap-3 bg-white text-green-800 border-l-4 border-green-500 px-6 py-4 rounded-lg shadow-xl z-50 max-w-md w-full"
                >
                    <!-- Icône succès -->
                    <div class="success-icon-container">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <!-- Message -->
                    <div class="flex-1">
                        <p class="font-semibold text-lg">Message envoyé !</p>
                        <p class="text-gray-600">Merci de nous avoir contacté. Nous vous répondrons dans les plus brefs délais.</p>
                    </div>

                    <!-- Bouton de fermeture -->
                    <button
                        @click="showSuccessPopup = false"
                        class="text-gray-400 hover:text-gray-600 transition"
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
            
            <div class="flex flex-col lg:flex-row gap-10 items-stretch justify-between max-w-7xl mx-auto">
                <!-- Formulaire -->
                <div 
                    :class="['transition-all duration-700 transform', isFormVisible ? 'translate-x-0 opacity-100' : '-translate-x-20 opacity-0']"
                    class="bg-white shadow-2xl rounded-2xl p-8 w-full lg:w-3/5 relative overflow-hidden"
                >
                    <div class="color-accent-top"></div>
                    <div class="color-accent-bottom"></div>
                    
                    <h2 class="text-2xl font-bold mb-8 text-center text-gray-800">Envoyez-nous un message</h2>
                    
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Nom & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="form-field-container">
                                <label class="form-label">Nom</label>
                                <input 
                                    v-model="form.name" 
                                    type="text" 
                                    @focus="focusField"
                                    :class="['form-input', checkField('name')]"
                                    required 
                                />
                                <span class="form-field-indicator"></span>
                            </div>
                            <div class="form-field-container">
                                <label class="form-label">Email</label>
                                <input 
                                    v-model="form.email" 
                                    type="email" 
                                    @focus="focusField"
                                    :class="['form-input', checkField('email')]"
                                    required 
                                />
                                <span class="form-field-indicator"></span>
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div class="form-field-container">
                            <label class="form-label">Catégorie</label>
                            <select 
                                v-model="form.category" 
                                @focus="focusField"
                                class="form-select"
                            >
                                <option v-for="option in categories" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <span class="form-field-indicator"></span>
                        </div>

                        <!-- Message -->
                        <div class="form-field-container">
                            <label class="form-label">Message</label>
                            <textarea 
                                v-model="form.message" 
                                rows="4" 
                                @focus="focusField"
                                :class="['form-textarea', checkField('message')]"
                                required
                            ></textarea>
                            <span class="form-field-indicator"></span>
                        </div>

                        <!-- Fichier -->
                        <div class="mt-6">
                            <label class="form-label mb-2 inline-block">Joindre un fichier (optionnel)</label>
                            <div class="file-upload-container">
                                <input 
                                    type="file" 
                                    id="file-upload" 
                                    @change="handleFileChange" 
                                    class="hidden" 
                                />
                                <label for="file-upload" class="file-upload-button">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i>
                                    {{ fileName ? 'Changer de fichier' : 'Choisir un fichier' }}
                                </label>
                                <span v-if="fileName" class="file-name">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    {{ fileName }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Taille maximum: 2 Mo</p>
                        </div>

                        <button 
                            type="submit" 
                            class="submit-button"
                        >
                            <span>Envoyer</span>
                            <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>

                <!-- Infos Contact -->
                <div 
                    :class="['transition-all duration-700 transform', isInfoVisible ? 'translate-x-0 opacity-100' : 'translate-x-20 opacity-0']"
                    class="lg:w-2/5 space-y-8 flex flex-col justify-between"
                >
                    <div class="contact-image-container">
                       
                        <img src="/images/support-person.png" alt="Support" class="support-person-image" />
                    </div>
                    
                    <!-- Cards d'information de contact -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Téléphone -->
                        <div class="contact-info-card">
                            <div class="contact-info-icon-container">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Téléphone</h4>
                                <p class="text-blue-600">+33 99532366</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="contact-info-card">
                            <div class="contact-info-icon-container">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Email</h4>
                                <p class="text-blue-600">braindcode@gmail.com</p>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="contact-info-card">
                            <div class="contact-info-icon-container">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">WhatsApp</h4>
                                <p class="text-blue-600">+33 99532366</p>
                            </div>
                        </div>

                        <!-- Horaires -->
                        <div class="contact-info-card">
                            <div class="contact-info-icon-container">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Horaires</h4>
                                <p class="text-gray-600">Lun - Ven: 9h - 18h</p>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</template>

<style scoped>
/* Base styles */
.main-title {
    font-size: 3rem;
    font-weight: 800;
    color: #253d4d;
    font-family: 'Poppins', sans-serif;
    display: inline-block;
    position: relative;
}

.title-underline {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 10%;
    width: 80%;
    height: 4px;
    background-color: #00AEEF;
    border-radius: 2px;
    transform-origin: left;
    animation: growLine 1.2s ease-out forwards;
}

@keyframes growLine {
    0% { transform: scaleX(0); }
    100% { transform: scaleX(1); }
}

/* Form styles */
.form-field-container {
    position: relative; 
    margin-bottom: 5px;
}

.form-label {
    display: block;
    font-weight: 500;
    margin-bottom: 6px;
    color: #374151;
    transition: all 0.3s ease;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
    background-color: #f9fafb;
    color: #1f2937;
    font-size: 1rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.form-input:focus, .form-select:focus, .form-textarea:focus,
.form-input.field-focused, .form-select.field-focused, .form-textarea.field-focused {
    outline: none;
    border-color: #00AEEF;
    box-shadow: 0 0 0 3px rgba(0, 174, 239, 0.15);
    background-color: #fff;
    transform: translateY(-2px);
}

.form-input.field-filled, .form-textarea.field-filled {
    border-left: 3px solid #10B981;
}

.form-field-indicator {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 2px;
    width: 0;
    background-color: #00AEEF;
    transition: width 0.3s ease;
}

.form-input:focus ~ .form-field-indicator,
.form-select:focus ~ .form-field-indicator,
.form-textarea:focus ~ .form-field-indicator {
    width: 100%;
}

/* File upload */
.file-upload-container {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.file-upload-button {
    display: inline-flex;
    align-items: center;
    background-color: #f3f4f6;
    color: #374151;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px dashed #d1d5db;
}

.file-upload-button:hover {
    background-color: #e5e7eb;
    color: #1f2937;
}

.file-name {
    display: inline-flex;
    align-items: center;
    background-color: #e0f7fa;
    color: #006064;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
    max-width: 250px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Submit button */
.submit-button {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    background-color: #00AEEF;
    color: white;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 174, 239, 0.25);
}

.submit-button:hover {
    background-color: #0098d3;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 174, 239, 0.3);
}

.submit-button:active {
    transform: translateY(0);
}

.submit-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.7s;
}

.submit-button:hover::before {
    left: 100%;
}

/* Contact info cards */
.contact-info-card {
    display: flex;
    align-items: center;
    gap: 16px;
    background-color: white;
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.contact-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.05);
}

.contact-info-icon-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #ebf8ff;
    color: #00AEEF;
    font-size: 1.25rem;
}

/* Social Media */
.social-media-container {
    text-align: center;
}

.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: white;
    color: #00AEEF;
    font-size: 1.25rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.social-icon:hover {
    background-color: #00AEEF;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}

/* Support person image */
.contact-image-container {
    position: relative;
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.support-person-image {
    max-width: 100%;
    height: auto;
    border-radius: 15px;
    filter: drop-shadow(0 10px 8px rgba(0, 0, 0, 0.04)) drop-shadow(0 4px 3px rgba(0, 0, 0, 0.1));
    animation: float 5s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.help-bubble {
    position: absolute;
    top: 10%;
    right: 15%;
    background-color: white;
    color: #253d4d;
    padding: 12px 20px;
    border-radius: 20px;
    border-bottom-right-radius: 0;
    font-weight: 500;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    animation: fadeInPopUp 0.8s ease-out forwards;
    transform-origin: bottom right;
    max-width: 200px;
    z-index: 2;
}

.help-bubble::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: -10px;
    width: 20px;
    height: 20px;
    background-color: white;
    clip-path: polygon(0 0, 0% 100%, 100% 100%);
}

.help-bubble p {
    margin: 0;
}

@keyframes fadeInPopUp {
    0% { opacity: 0; transform: scale(0.8); }
    50% { opacity: 1; transform: scale(1.05); }
    100% { opacity: 1; transform: scale(1); }
}

/* Color accents */
.color-accent-top {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #00AEEF, #83d0f5);
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
}

.color-accent-bottom {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 30%;
    height: 5px;
    background: linear-gradient(90deg, #83d0f5, #00AEEF);
    border-bottom-right-radius: 16px;
}

/* Animations */
.animate-fade-in-down {
    animation: fadeInDown 0.8s ease-out;
}

@keyframes fadeInDown {
    0% { opacity: 0; transform: translateY(-20px); }
    100% { opacity: 1; transform: translateY(0); }
}

.slide-down-enter-active, .slide-down-leave-active {
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.slide-down-enter-from {
    transform: translateY(-20px) translateX(-50%);
    opacity: 0;
}

.slide-down-leave-to {
    transform: translateY(-20px) translateX(-50%);
    opacity: 0;
}

/* Success icon animation */
.success-icon-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #ecfdf5;
    margin-top: 2px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Confetti animation */
.confetti {
    position: absolute;
    top: -10px;
    display: block;
    border-radius: 0;
    animation: fall linear forwards;
}

@keyframes fall {
    to {
        transform: translateY(105vh) rotate(720deg);
    }
}

/* Floating shapes */
.floating-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
}

.shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.05;
    pointer-events: none;
}

.shape-1 {
    top: 20%;
    left: 10%;
    width: 300px;
    height: 300px;
    background-color: #00AEEF;
    animation: floatAnimation 15s infinite alternate ease-in-out;
}

.shape-2 {
    top: 60%;
    right: 5%;
    width: 200px;
    height: 200px;
    background-color: #00AEEF;
    animation: floatAnimation 12s infinite alternate-reverse ease-in-out;
}

.shape-3 {
    bottom: 10%;
    left: 20%;
    width: 150px;
    height: 150px;
    background-color: #00AEEF;
    animation: floatAnimation 20s infinite alternate ease-in-out;
}

.shape-4 {
    top: 30%;
    right: 30%;
    width: 100px;
    height: 100px;
    background-color: #00AEEF;
    animation: floatAnimation 18s infinite alternate-reverse ease-in-out;
}

@keyframes floatAnimation {
    0% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(30px, 20px) rotate(5deg); }
    66% { transform: translate(-20px, 40px) rotate(-5deg); }
    100% { transform: translate(10px, -30px) rotate(0deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .main-title {
        font-size: 2.5rem;
    }
    
    .help-bubble {
        top: 5%;
        right: 25%;
    }
}

@media (max-width: 640px) {
    .main-title {
        font-size: 2rem;
    }
    
    .contact-info-card {
        flex-direction: column;
        text-align: center;
    }
    
    .contact-info-icon-container {
        margin: 0 auto 10px;
    }
    
    .help-bubble {
        display: none;
    }
}
</style>