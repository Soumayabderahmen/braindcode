<template>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card card-1 cardNotifs">
                <div class="card-body p-0">
                    <div class="box px-3" v-for="item in notifications" :key="item.id">
                        <div class="d-flex align-items-start">
                            <div class="icon" v-html="getIcon(item.type)"></div>
                            <div>
                                <h5>{{ item.title }}</h5>
                                <p>{{ item.time }} | Le {{ item.date }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-rect me-2"><i class="ti ti-eye"></i></button>
                            <button class="btn btn-rect bg-danger text-white me-2"><i class="ti ti-x"></i></button>
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
    data() {
        return {
            notifications: [
                { 'title': 'Nom prénom 1 a ajouté son daily', 'time': 'Il y a 2h', 'date': '10/01/2025', 'type': 'daily' },
                { 'title': 'Rappel Rendez-vous avec client 1', 'time': 'Dans 30 min', 'date': '10/01/2025', 'type': 'rendezVous' },
                { 'title': 'Projet 1 : tâche terminé', 'time': 'Dans 30 min', 'date': '10/01/2025', 'type': 'task' },
                { 'title': 'Nom prénom 2 a demandé un congé', 'time': 'Il y a 2h', 'date': '10/01/2025', 'type': 'conge' },
            ],
            icons: {
                daily: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 27 30" fill="none">
                <path d="M12.1111 27.5263H3.77778C3.04107 27.5263 2.33453 27.2158 1.81359 26.663C1.29266 26.1103 1 25.3606 1 24.5789V6.89474C1 6.11304 1.29266 5.36337 1.81359 4.81063C2.33453 4.25789 3.04107 3.94737 3.77778 3.94737H20.4444C21.1812 3.94737 21.8877 4.25789 22.4086 4.81063C22.9296 5.36337 23.2222 6.11304 23.2222 6.89474V13.5263M17.6667 1V6.89474M6.55556 1V6.89474M1 12.7895H23.2222M26 29C26 28.2183 25.7073 27.4686 25.1864 26.9159C24.6655 26.3632 23.9589 26.0526 23.2222 26.0526H20.4444C19.7077 26.0526 19.0012 26.3632 18.4803 26.9159C17.9593 27.4686 17.6667 28.2183 17.6667 29M19.0556 21.6316C19.0556 22.4133 19.3482 23.1629 19.8691 23.7157C20.3901 24.2684 21.0966 24.5789 21.8333 24.5789C22.57 24.5789 23.2766 24.2684 23.7975 23.7157C24.3185 23.1629 24.6111 22.4133 24.6111 21.6316C24.6111 20.8499 24.3185 20.1002 23.7975 19.5475C23.2766 18.9947 22.57 18.6842 21.8333 18.6842C21.0966 18.6842 20.3901 18.9947 19.8691 19.5475C19.3482 20.1002 19.0556 20.8499 19.0556 21.6316Z" stroke="#7E57C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>`,
                rendezVous: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="#7E57C2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M11.795 21H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M14 18a4 4 0 1 0 8 0a4 4 0 1 0-8 0m1-15v4M7 3v4m-4 4h16"/><path d="M18 16.496V18l1 1"/></g></svg>`,
                task: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="#7E57C2" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M3.5 9.368c0-3.473 0-5.21 1.025-6.289S7.2 2 10.5 2h3c3.3 0 4.95 0 5.975 1.08C20.5 4.157 20.5 5.894 20.5 9.367v5.264c0 3.473 0 5.21-1.025 6.289S16.8 22 13.5 22h-3c-3.3 0-4.95 0-5.975-1.08C3.5 19.843 3.5 18.106 3.5 14.633zM13.5 11H17"/><path d="M7 12s.5 0 1 1c0 0 1.588-2.5 3-3m2.5 7H17M8 2l.082.493c.2 1.197.3 1.796.72 2.152C9.22 5 9.827 5 11.041 5h1.917c1.213 0 1.82 0 2.24-.355c.42-.356.52-.955.719-2.152L16 2M8 17h1"/></g></svg>`,
                conge: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="#7E57C2" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M12 15v1.5M3 11l.153 2.863c.164 3.614.246 5.42 1.406 6.529S7.527 21.5 11.145 21.5h1.71c3.618 0 5.427 0 6.586-1.108s1.242-2.915 1.406-6.529L21 11"/><path d="M2.847 10.443C4.547 13.674 8.38 15 12 15s7.453-1.326 9.153-4.557C21.964 8.901 21.35 6 19.352 6H4.648c-1.998 0-2.612 2.9-1.8 4.443M16 6l-.088-.31c-.44-1.54-.66-2.31-1.184-2.75s-1.22-.44-2.611-.44h-.234c-1.391 0-2.087 0-2.61.44c-.525.44-.745 1.21-1.185 2.75L8 6"/></g></svg>`,
                default: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2a7 7 0 00-7 7c0 5-2 6-2 6h18s-2-1-2-6a7 7 0 00-7-7z" stroke="#7E57C2" stroke-width="2"/>
                    <path d="M9 19h6M10 22h4" stroke="black" stroke-width="2"/>
                </svg>`
            }
        };
    },
    methods: {
        getIcon(type) {
            return this.icons[type] || this.icons.default;
        }
    }
};
</script>