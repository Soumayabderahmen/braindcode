<template>
    <div class="row formulaire mt-4">
        <div class="col-12 mb-4">
            <p class="description">
                Découvrez nos Agents AI spécialisés pour optimiser la gestion et le développement de votre startup. Que
                ce soit pour analyser vos données, gérer vos finances ou améliorer votre stratégie marketing, ces
                assistants intelligents vous apportent un soutien efficace et personnalisé.
            </p>
        </div>
    </div>
    <div class="mt-3  formulaire">
        <div class="col-12">
            <div class="card card-1">
                <div class="card-body px-lg-4 px-md-3 px-2">
                    <div class="bs-stepper wizard-numbered shadow-none mt-2">
                        <div class="bs-stepper-header border-0 px-lg-5 px-md-4 px-2">
                            <div class="step" :class="{ 'active': currentStep === 1 }" data-target="#account-details">
                                <button type="button" class="step-trigger p-0" @click="goToStep(1)">
                                    <span class="bs-stepper-circle shadow-none mx-0">1</span>
                                </button>
                            </div>
                            <div class="line" :class="{ 'active-line': currentStep >= 1 }">
                            </div>
                            <div class="step" :class="{ 'active': currentStep === 2 }" data-target="#personal-info">
                                <button type="button" class="step-trigger p-0" @click="goToStep(2)">
                                    <span class="bs-stepper-circle">2</span>
                                </button>
                            </div>
                            <div class="line" :class="{ 'active-line': currentStep >= 2 }">
                            </div>
                            <div class="step" :class="{ 'active': currentStep === 3 }" data-target="#social-links">
                                <button type="button" class="step-trigger p-0" @click="goToStep(3)">
                                    <span class="bs-stepper-circle">3</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content px-2">
                            <div v-if="currentStep === 1" class="row">
                                <div class="col-12 mb-4">
                                    <h4 class="">Paramétrez les coordonnées de votre agent IA</h4>
                                    <p class="text-orange fw-bold">*Représente un champ obligatoire</p>
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="" class="form-label">Nom de l'agent IA*</label>
                                    <input type="text" class="form-control" placeholder="Entrez un nom">
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="" class="form-label">Domaine d'expertise *</label>
                                    <input type="number" class="form-control" placeholder="Entrez un domaine">
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 mb-4">
                                    <label for="" class="form-label">Type d'agent *</label>
                                    <div class="d-flex flex-wrap">
                                        <div class="form-check me-3">
                                            <input type="radio" id="public" name="type" class="form-check-input">
                                            <label for="public" class="form-label">Agent IA public</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" id="public" name="type" class="form-check-input">
                                            <label for="public" class="form-label">Agent IA privé</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12 mb-4">
                                    <label for="" class="form-label">Prix de l'agent*</label>
                                    <input type="number" class="form-control" placeholder="Entrer un prix">
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="" class="form-label">Ajouter des tags</label>
                                    <div class="input-group mb-4">
                                        <input type="text" class="form-control" v-model="newTag" @keyup.enter="addTag"
                                            placeholder="Entrer un tag">
                                        <button class="btn btn-primary rounded-15" @click="addTag">Ajouter</button>
                                    </div>
                                    <div class="tags">
                                        <button v-for="(tag, index) in tags" :key="index" class="tagBtn btn">
                                            <span class="me-2">#{{ tag }}</span>
                                            <button class="btn btnTrash p-0" @click="removeTag(index)">
                                                <i class="ti ti-circle-x text-danger"></i>
                                            </button>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="" class="form-label">Description de l'agent IA</label>
                                    <textarea name="" id="" rows="4" class="form-control"
                                        placeholder="Décrire votre agent..."></textarea>
                                </div>
                                <div class="col-12 mb-4">
                                    <div class="card card-1">
                                        <div class="card-body d-block">
                                            <label for="" class="form-label">Responsabilité de l'agent IA</label>
                                            <div class="input-group mb-4">
                                                <input type="text" class="form-control" v-model="newResponsibility"
                                                    @keyup.enter="addResponsibility"
                                                    placeholder="Entrer une responsabilité">
                                                <button class="btn btn-primary rounded-15"
                                                    @click="addResponsibility">Ajouter</button>
                                            </div>
                                            <div class="tags responsibilities">
                                                <div v-for="(responsibility, index) in responsibilities" :key="index"
                                                    class="responsibility-item btn tagBtn mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ responsibility }}</span>
                                                        <button class="btn btnTrash p-0"
                                                            @click="removeResponsibility(index)">
                                                            <i class="ti ti-circle-x text-danger"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-block">
                                                <button class="btn btn-primary mb-4 fs-sm">Ajouter un
                                                    sous-titre</button><br>
                                                <button class="btn btn-primary mb-4 fs-sm">Ajouter une nouvelle
                                                    responsabilité</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="currentStep === 2" class="row">
                                <div class="col-12 mb-4">
                                    <h4 class="">Entraînez votre agent IA </h4>
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="" class="form-label">Associer des liens</label>
                                    <div class="addBox justify-content-between p-lg-3 p-md-2 p-2">
                                        <p class="me-2">Exemple : https//www.youtube.com</p>
                                        <button class="btn btn-primary fs-sm">Ajouter un autre lien</button>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="" class="form-label">Ajouter un document</label>
                                    <div class="addBox p-lg-3 p-md-2 p-2">
                                        <button class="btn btn-primary fs-sm me-2 mb-2">Séléctionner un
                                            fichier</button>
                                        <button class="btn btn-primary fs-sm me-2 mb-2">Sélectionner depuis la
                                            bibliothèque</button>
                                        <p class="me-2">Aucun fichier choisi</p>

                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="" class="form-label">Description de la mission</label>
                                    <textarea name="" rows="5" id="" class="form-control"
                                        placeholder="Ecrire  içi..."></textarea>
                                </div>
                            </div>
                            <div v-if="currentStep === 3" class="row">
                                <div class="col-12 mb-4">
                                    <h4 class="">Automatiser votre agent IA</h4>
                                    <p class="text-orange fw-bold">*Représente un champ obligatoire</p>
                                </div>
                            </div>
                            <div class="col-12 mb-4 mt-4 d-flex"
                                :class="{ 'justify-content-between': currentStep > 1, 'justify-content-end': currentStep === 1 }">
                                <button v-if="currentStep > 1" class="btn btn-primary fs-sm px-3 py-2"
                                    @click="prevStep">
                                    Précédent
                                </button>
                                <button class="btn btn-primary fs-sm px-3 py-2" @click="nextStep">
                                    {{ currentStep === 3 ? 'Enregistrer' : 'Suivant' }}
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>
<script>
import 'bootstrap';
import '@popperjs/core';
export default {
name: 'addAgentIA',


    data() {
        return {
            currentStep: 1, // Étape initiale
            tags: [],
            newTag: '',
            responsibilities: [],
            newResponsibility: '',
        };
    },
    mounted() {

    },
    methods: {
        nextStep() {
            if (this.currentStep < 3) {
                this.currentStep += 1;
            }
        },
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep -= 1;
            }
        },
        goToStep(step) {
            if (step >= 1 && step <= 3) {
                this.currentStep = step;
            }
        },
        addTag() {
            if (this.newTag.trim() && !this.tags.includes(this.newTag.trim())) {
                this.tags.push(this.newTag.trim());
                this.newTag = '';
            }
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        addResponsibility() {
            if (this.newResponsibility.trim() && !this.responsibilities.includes(this.newResponsibility.trim())) {
                this.responsibilities.push(this.newResponsibility.trim());
                this.newResponsibility = '';
            }
        },
        removeResponsibility(index) {
            this.responsibilities.splice(index, 1);
        },
    },


};
</script>