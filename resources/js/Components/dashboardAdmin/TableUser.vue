<template>
    <div class="card card-1 cardDash">
        <div class="card-header d-lg-flex d-md-flex d-sm-flex d-block">
            <h5>Dernier Utilisateurs Dans la Plateforme</h5>
            <a href="">Voir tous <i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="card-body">
            <div class="col-12 table-responsive">
                <table id="avancementsTable" class="table table-1 w-100">
                    <thead>
                        <tr>
                            <th class="w-25"><center>Nom & Prénom</center></th>
                            <th class="w-25"><center>Email</center></th>
                            <th class="w-25"><center>Rôle</center></th>
                            <th class="w-25"><center>Statut</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                            
                            <td><span><center>{{ user.name }}</center></span> </td>

                            <td>
                                    <span><center>{{ user.email }}</center></span>


                            </td>
                            <td>
                                    <span><strong><center>{{ user.role }}</center></strong></span>
                                    

                            </td>
                            <td>
                                <center><span class="badge text-white" :class="getNoteClass(user.statut)">
                                    <strong>{{ user.statut }}</strong>
                                </span></center>
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
    name: 'tableUser',
    props:{
        users:Array
    },
    
    methods: {
        getNoteClass(statut) {
            if (statut === "active") {
                return 'bg-info';
             } else{
                return 'bg-yellow';
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