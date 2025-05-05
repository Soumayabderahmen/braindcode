import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, defineAsyncComponent} from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { toast } from 'vue3-toastify'; 

// Composants Vue utilisés dans les Blade classiques


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const appElement = document.getElementById('app');

if (appElement && appElement.hasAttribute('data-page')) {
    // ➤ Page Inertia.js
    createInertiaApp({
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue'),
            ),
        setup({ el, App, props, plugin }) {
            return createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue)
                .mount(el);
        },
        progress: {
            color: '#4B5563',
        },
    });
} else if (appElement) {
    // ➤ Page Blade classique avec Vue
    const app = createApp({});

    // Enregistrement des composants utilisés dans Blade
    app.component('top-cards-dashbord', defineAsyncComponent(() => import('./Components/dashbord/top-cards.vue')));
    app.component('avancements-dashbord', defineAsyncComponent(() => import('./Components/dashbord/avancements.vue')));
    app.component('top-card', defineAsyncComponent(() => import('./Components/dashboardAdmin/topCards.vue')));
    app.component('table-user', defineAsyncComponent(() => import('./Components/dashboardAdmin/TableUser.vue')));
    app.component('liste-agents-ia', defineAsyncComponent(() => import('./Components/agentsIA/liste.vue')));
    app.component('add-agent-ia', defineAsyncComponent(() => import('./Components/agentsIA/add.vue')));    
    app.component('details-agent-ia',defineAsyncComponent(() => import('./Components/agentsIA/details.vue')));
    app.component('calendrier',defineAsyncComponent(() => import('./Pages/Startups/Calander.vue')));
    app.component('list-reservations',defineAsyncComponent(() => import('./Pages/Admin/ListReservations.vue')));
    app.component('disponibilite',defineAsyncComponent(() => import('./Pages/Coach/Availability.vue')));
    app.component('coach-calender',defineAsyncComponent(() => import('./Pages/Coach/Calandry.vue')));
    app.component('reservations-coach',defineAsyncComponent(() => import('./Pages/Coach/ListeReservation.vue')));
    app.component('coachs',defineAsyncComponent(() => import('./Pages/Admin/ActivateCoach.vue')));
    app.component('investisseur',defineAsyncComponent(() => import('./Pages/Admin/investisseur.vue')));
    app.component('startups',defineAsyncComponent(() => import('./Pages/Admin/startup.vue')));
    app.component('add-reservations',defineAsyncComponent(() => import('./Pages/Startups/ReservationCoach.vue')));
    app.component('notification',defineAsyncComponent(() => import('./Components/Notifications.vue')));


    app.config.globalProperties.$toast = Object.assign(toast, {   
        success: (msg, opts) => toast(msg, { type: "success", ...opts }),   
        error: (msg, opts) => toast(msg, { type: "error", ...opts }),   
        info: (msg, opts) => toast(msg, { type: "info", ...opts }),   
        warn: (msg, opts) => toast(msg, { type: "warn", ...opts }), 
      });  

    app.mount('#app');


    

}
