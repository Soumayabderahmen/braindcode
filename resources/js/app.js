import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { toast } from 'vue3-toastify'; 

// Composants Vue utilisés dans les Blade classiques
import TopCards from './Components/dashbord/top-cards.vue';
import AvancementsDashbord from './Components/dashbord/avancements.vue';
import topCard from './Components/dashboardAdmin/topCards.vue';
import TableUser from './Components/dashboardAdmin/TableUser.vue';
import ListeAgentsIA from './Components/agentsIA/liste.vue';
import AddAgentIA from './Components/agentsIA/add.vue';
import detailsAgentsIA from './Components/agentsIA/details.vue';
import Calander from './Pages/Startups/Calander.vue';
import ListReservations from './Pages/Admin/ListReservations.vue';
import disponibilite from './Pages/Coach/Availability.vue'


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
    app.component('top-cards-dashbord', TopCards);
    app.component('avancements-dashbord', AvancementsDashbord);
    app.component('top-card', topCard);
    app.component('table-user', TableUser);
    app.component('liste-agents-ia', ListeAgentsIA);
    app.component('add-agent-ia', AddAgentIA);    
    app.component('details-agent-ia', detailsAgentsIA);
    app.component('calendrier', Calander);
    app.component('list-reservations', ListReservations);
    app.component('disponibilite', disponibilite);

    
    app.config.globalProperties.$toast = Object.assign(toast, {   
        success: (msg, opts) => toast(msg, { type: "success", ...opts }),   
        error: (msg, opts) => toast(msg, { type: "error", ...opts }),   
        info: (msg, opts) => toast(msg, { type: "info", ...opts }),   
        warn: (msg, opts) => toast(msg, { type: "warn", ...opts }), 
      });  

    app.mount('#app');



}
