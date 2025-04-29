<template>
    <div class="row">
        <div class="col-12 mb-4">
            <p class="description">
                Découvrez nos Agents AI spécialisés pour optimiser la gestion et le développement de votre startup. Que
                ce soit pour analyser vos données, gérer vos finances ou améliorer votre stratégie marketing, ces
                assistants intelligents vous apportent un soutien efficace et personnalisé.
            </p>
        </div>
        <div class="col-12 mb-4">
            <div class="nav-align-top shadow-none mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-agents" aria-controls="navs-top-agents" aria-selected="true">Mes
                            agents IA
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-solde" aria-controls="navs-top-solde" aria-selected="false">Solde
                            de vente des agents
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-marketplace" aria-controls="navs-top-marketplace"
                            aria-selected="false">Marketplace
                        </button>
                    </li>
                </ul>
                <div class="tab-content px-0">
                    <!--mes agents-->
                    <div class="tab-pane fade show active" id="navs-top-agents" role="tabpanel">
                        <p>
                            Icing pastry pudding oat cake. Lemon drops cotton candy caramels cake caramels sesame snaps
                            powder. Bear
                            claw
                            candy topping.
                        </p>
                        <p class="mb-0">
                            Tootsie roll fruitcake cookie. Dessert topping pie. Jujubes wafer carrot cake jelly. Bonbon
                            jelly-o
                            jelly-o ice
                            cream jelly beans candy canes cake bonbon. Cookie jelly beans marshmallow jujubes sweet.
                        </p>
                    </div>
                    <!--solde-->
                    <div class="tab-pane fade" id="navs-top-solde" role="tabpanel">
                        <p>
                            Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice
                            cream. Gummies
                            halvah
                            tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice cream cheesecake
                            fruitcake.
                        </p>
                        <p class="mb-0">
                            Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah
                            cotton candy
                            liquorice caramels.
                        </p>
                    </div>
                    <!--marketplace-->
                    <div class="tab-pane fade" id="navs-top-marketplace" role="tabpanel">
                        <div class="row filters">
                            <div
                                class="col-lg-8 col-md-8 col-sm-8 col-12 d-lg-flex d-md-flex d-sm-flex d-block justify-content-start align-items-center">
                                <div class="input-group me-2 mb-4">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                            viewBox="0 0 19 19" fill="none">
                                            <path
                                                d="M17.8749 18L12.4244 12.3333M1.52344 7.61111C1.52344 8.47929 1.68792 9.33898 2.00748 10.1411C2.32705 10.9432 2.79544 11.672 3.38592 12.2859C3.9764 12.8998 4.6774 13.3867 5.4489 13.719C6.22039 14.0512 7.04728 14.2222 7.88234 14.2222C8.71741 14.2222 9.54429 14.0512 10.3158 13.719C11.0873 13.3867 11.7883 12.8998 12.3788 12.2859C12.9692 11.672 13.4376 10.9432 13.7572 10.1411C14.0768 9.33898 14.2412 8.47929 14.2412 7.61111C14.2412 6.74293 14.0768 5.88325 13.7572 5.08115C13.4376 4.27905 12.9692 3.55025 12.3788 2.93635C11.7883 2.32245 11.0873 1.83548 10.3158 1.50324C9.54429 1.171 8.71741 1 7.88234 1C7.04728 1 6.22039 1.171 5.4489 1.50324C4.6774 1.83548 3.9764 2.32245 3.38592 2.93635C2.79544 3.55025 2.32705 4.27905 2.00748 5.08115C1.68792 5.88325 1.52344 6.74293 1.52344 7.61111Z"
                                                stroke="#C6C6C6" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Chercher">
                                </div>
                                <select name="" id="" class="form-select mb-4">
                                    <option value="" selected disabled>Catégorie</option>
                                </select>
                            </div>
                            <div
                                class="col-lg-4 col-md-4 col-sm-4 col-12 d-lg-flex d-md-flex d-sm-flex d-block justify-content-end align-items-center">
                                <a :href="'/agentia/add'" class="btn btn-primary fs-sm mb-4">
                                    Créer un Agent IA
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-1">
                                    <div class="card-body">
                                        <div class="row">
                                            <div v-for="item in marketplace" :key="item.id"
                                                class="col-lg-4 col-md-4 col-sm-6 col-12 mb-4">
                                                <div class="card card-1 cardAgent">
                                                    <div class="card-header pb-2">
                                                        <img src="/assets/img/dash/robot_smile.png" alt="">
                                                        <h4>{{ item.name }}</h4>
                                                        <h5>{{ item.type }}</h5>
                                                    </div>
                                                    <div class="card-body pb-0">
                                                        <p>{{ item.description }}</p>
                                                    </div>
                                                    <div class="card-footer btns pt-3">
                                                        <a :href="'/agentia/details'" class="btn btn-primary fs-sm mb-2">Voir plus</a>
                                                        <button class="btn btn-outline-orange fs-sm mb-2">{{ item.price }}
                                                            €</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AgentsIAListe',
    data() {
        return {
            activeTab: 'marketplace', // Default active tab
            marketplace: [
                {
                    id: 1,
                    name: 'MarkiBot',
                    type: 'Assistant marketing digital',
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    price: 10,
                    image: '/images/robot-icon.png'
                },
                {
                    id: 2,
                    name: 'Growthy',
                    type: 'Assistant campagnes publicitaires',
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    price: 10,
                    image: '/images/robot-icon.png'
                },
                {
                    id: 3,
                    name: 'Growthy',
                    type: 'Assistant campagnes publicitaires',
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    price: 10,
                    image: '/images/robot-icon.png'
                },
                {
                    id: 4,
                    name: 'Growthy',
                    type: 'Assistant campagnes publicitaires',
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    price: 10,
                    image: '/images/robot-icon.png'
                },
                {
                    id: 5,
                    name: 'Growthy',
                    type: 'Assistant campagnes publicitaires',
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    price: 10,
                    image: '/images/robot-icon.png'
                },
                {
                    id: 6,
                    name: 'Growthy',
                    type: 'Assistant campagnes publicitaires',
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    price: 10,
                    image: '/images/robot-icon.png'
                },
            ]
        }
    }
}
</script>
