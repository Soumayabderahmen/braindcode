import '../css/app.css';
import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import TopCards from './Components/dashbord/top-cards.vue';
import FaqAdmin from './Pages/Admin/FaqAdmin.vue'
import Support from './Pages/Admin/SupportMessages.vue'
import ViewMessage  from './Pages/Admin/ViewMessage.vue'
import Chat from './Pages/Admin/ChatbotAdmin.vue'
import Reaction from './Pages/Admin/ChatbotReactions.vue'
import Chatbot from './Pages/Chatbot/Chatbot.vue';
import ChatbotManagement from './Pages/Admin/ChatbotManagement.vue'
import AvancementsDashbord from './Components/dashbord/avancements.vue';
import Home from './Pages/Home.vue'
import Faq from './Pages/Faq/Faq.vue'
import Contact from './Pages/ContactUs/ContactUs.vue'
import StartupPage from './Pages/Home/StartupPage.vue';
import CoachPage from './Pages/Home/CoachPage.vue';
import InvestorPage from './Pages/Home/InvestorPage.vue';
import ForumPage from './Pages/Home/ForumPage.vue';
import EquipePage from './Pages/Home/EquipePage.vue';
import StartupIncubePage from './Pages/Home/StartupIncubePage.vue';
import FormationPage from './Pages/Home/FormationPage.vue';
import RessourcesPage from './Pages/Home/RessourcesPage.vue';
import AgentAIPage from './Pages/Home/AgentAIPage.vue';
import AgentDetailsPage from './Pages/Home/AgentDetailsPage.vue';


import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const appElement = document.getElementById('app');

if (appElement && appElement.hasAttribute('data-page')) {

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

}
else if (appElement) {
    // ➤ Page Blade classique avec Vue
    const app = createApp({});
    
    // Enregistrement des composants utilisés dans Blade
  
    app.component('chatbotia', Chatbot)
    app.component('reaction', Reaction)
    app.component('chatbot', Chat)
    app.component('chatbot-management', ChatbotManagement);
    app.component('view-message', ViewMessage)
    app.component('support', Support);
    app.component('faq', FaqAdmin);
    app.component('top-cards-dashbord', TopCards);
    app.component('avancements-dashbord', AvancementsDashbord);
    app.component('home-view', Home);
    app.component('faqs', Faq);
    app.component('contact', Contact);
    app.component('startup-page', StartupPage);
    app.component('coach-page', CoachPage);
    app.component('investor-page', InvestorPage);
    app.component('forum-page', ForumPage);
    app.component('equipe-page', EquipePage);
    app.component('startup-incube-page', StartupIncubePage);
    app.component('formation-page', FormationPage);
    app.component('ressources-page', RessourcesPage);
    app.component('agent-ai-page', AgentAIPage);
    app.component('agent-details-page', AgentDetailsPage);

    app.mount('#app');



    
}
