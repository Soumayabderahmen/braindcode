<template>
    <div class="row">
        <div
            class="col-lg-4 col-md-4 col-sm-6 col-12 mb-4"
            v-for="(card, index) in cards"
            :key="index"
        >
            <div class="card cardTop">
                <div class="card-header">
                    <img :src="card.icon" alt="" />
                </div>
                <div class="card-body">
                    <h5>{{ card.title }}</h5>
                </div>
                <div class="card-footer">
                    <p>{{ card.value }} {{ card.unit }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TopCardsDashbord',
    data() {
        return {
            cards: [
                {
                    icon: '/assets/img/dash/calendar.png',
                    title: 'Nombre de rendez-vous réalisés',
                    value: 25,
                    unit: 'rendez-vous'
                },
                {
                    icon: '/assets/img/dash/cart.png',
                    title: 'Nombre de formation achetées',
                    value: 150,
                    unit: 'formations'
                },
                {
                    icon: '/assets/img/dash/star.png',
                    title: 'Taux de recommandation sur le site web',
                    value: 67.4,
                    unit: '%'
                }
            ]
        };
    }
};
</script>
