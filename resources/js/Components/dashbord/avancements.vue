<template>
    <div class="card card-1 cardDash">
        <div class="card-header d-lg-flex d-md-flex d-sm-flex d-block">
            <h5>Dernier avancement des startup</h5>
            <a href="">Voir tous <i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="card-body">
            <div class="col-12 table-responsive">
                <table id="avancementsTable" class="table table-1 w-100">
                    <thead>
                        <tr>
                            <th class="w-25">Startup</th>
                            <th class="w-25">Formation</th>
                            <th class="w-25">Performance</th>
                            <th class="w-25">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in avancements" :key="item.id">
                            <td>
                                <div class="media media-2">
                                    <img :src="item.logo" alt="" class="rounded-circle">
                                </div>
                            </td>
                            <td><span>{{ item.formation }}</span> </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span><strong>{{ item.performances }}%</strong></span>
                                    <div class="progress w-100" style="height: 10px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            :style="{ width: item.performances + '%' }"
                                            :aria-valuenow="item.performances" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>


                            </td>
                            <td>
                                <span class="badge text-white" :class="getNoteClass(item.note)">
                                    <strong>{{ item.note }}/20</strong>
                                </span>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import 'bootstrap';
import '@popperjs/core';
export default {
    name: 'AvancementsDashbord',
    data() {
        return {
            fileName: "",
            avancements: [
                {
                    id: 1,
                    logo: 'assets/img/dash/logo1.png',
                    formation: "Formation",
                    performances: 45,
                    note: 13
                },
                {
                    id: 2,
                    logo: 'assets/img/dash/logo2.png',
                    formation: "Formation",
                    performances: 20,
                    note: 17
                },
                {
                    id: 3,
                    logo: 'assets/img/dash/logo3.png',
                    formation: "Formation",
                    performances: 60,
                    note: 14
                },
                {
                    id: 4,
                    logo: 'assets/img/dash/logo1.png',
                    formation: "Formation",
                    performances: 30,
                    note: 10
                },
            ],
        };
    },
    methods: {
        getNoteClass(note) {
            if (note >= 15 && note <= 20) {
                return 'bg-gray-dark';
            } else if (note >= 11 && note <= 14) {
                return 'bg-yellow';
            } else if (note >= 0 && note <= 10) {
                return 'bg-orange';
            } else {
                return ''; // au cas où la note est hors de 0-20
            }
        }
    },

    mounted() {
        this.$nextTick(() => {
            $("#avancementsTable").DataTable({
                searching: false, // Supprime la barre de recherche
                ordering: false,  // Désactive le tri
                paging: true,     // Active la pagination
                info: false,      // Masque "Showing X to Y of Z entries"
                language: {
                    paginate: {
                        previous: "", // Supprime "Précédent"
                        next: "",     // Supprime "Suivant"
                    },
                    lengthMenu: "Afficher _MENU_ entrées",
                    zeroRecords: "Aucun enregistrement trouvé",
                    infoEmpty: "",
                    infoFiltered: "(filtré à partir de _MAX_ entrées au total)",
                },
                dom: "tp", // Supprime les boutons inutiles (t = tableau, p = pagination)
            });
        });

    }


};
</script>