<script setup>
import Main from "@/Layouts/main.vue";
import { Head } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import 'bootstrap/dist/css/bootstrap.min.css';
import { computed } from "vue";


const page = usePage();

const props = defineProps({
  users: Array,
  reservations: Array,
});
const coachCount = computed(() => {
  return props.users.filter(user => user.role === 'coach').length;
});
const startupCount = computed(() => {
  return props.users.filter(user => user.role === 'startup').length;
});
const investCount = computed(() => {
  return props.users.filter(user => user.role === 'investisseur').length;
});
const reservationCount = computed(() => {
  return props.reservations.filter(reservation => reservation.statut === 'acceptée').length;
});
const filteredUsers = computed(() => {
  return props.users.filter(user => user.role !== 'admin');
});
</script>

<template>

  <Head title="Dashboard" />
  <link rel="stylesheet" href="/assets/css/bootstrap1.min.css" />
  <!-- themefy CSS -->
  <link rel="stylesheet" href="/assets/vendors/themefy_icon/themify-icons.css" />
  <!-- select2 CSS -->
  <link rel="stylesheet" href="/assets/vendors/niceselect/css/nice-select.css" />
  <!-- owl carousel CSS -->
  <link rel="stylesheet" href="/assets/vendors/owl_carousel/css/owl.carousel.css" />
  <!-- gijgo css -->
  <link rel="stylesheet" href="/assets/vendors/gijgo/gijgo.min.css" />
  <!-- font awesome CSS -->
  <link rel="stylesheet" href="/assets/vendors/font_awesome/css/all.min.css" />
  <link rel="stylesheet" href="/assets/vendors/tagsinput/tagsinput.css" />

  <!-- date picker -->

  <link rel="stylesheet" href="/assets/vendors/vectormap-home/vectormap-2.0.2.css" />

  <!-- scrollabe  -->
  <link rel="stylesheet" href="/assets/vendors/scroll/scrollable.css" />
  <!-- datatable CSS -->

  <!-- morris css -->
  <link rel="stylesheet" href="/assets/vendors/morris/morris.css">
  <!-- metarial icon css -->
  <link rel="stylesheet" href="/assets/vendors/material_icon/material-icons.css" />

  <!-- menu css  -->
  <link rel="stylesheet" href="/assets/css/metisMenu.css">
  <!-- style CSS -->
  <link rel="stylesheet" href="/assets/css/style1.css" />
  <link rel="stylesheet" href="/assets/css/colors/default.css" id="colorSkinCSS">
  <Main :showSidebar="true">
    <div class="main_content_iner overly_inner ">
      <div class="container-fluid p-0 ">
        <!-- page title  -->

        <div class="row ">


          <div class="col-xl-12 ">
            <div class="white_card card_height_100 mb_30 social_media_card" style="
    margin-right: -255px;
">
              <div class="white_card_header">
                <div class="main-title">
                  <h3 class="m-0"> 📈Statistique 📈</h3>
                  <span>Quelques Chiffres</span>
                </div>
              </div>
              <div class="media_thumb ml_25">
                <img src="img/media.svg" alt="">
              </div>
              <div class="media_card_body">
                <div class="media_card_list">
                  <div class="single_media_card">
                    <span>Nbre des Coach</span>
                    <h3>{{ coachCount }}</h3>
                  </div>
                  <div class="single_media_card">
                    <span>Nbre des Startups</span>
                    <h3>{{ startupCount }}</h3>
                  </div>
                  <div class="single_media_card">
                    <span>Nbre des investisseurs</span>
                    <h3>{{ investCount }}</h3>
                  </div>
                  <div class="single_media_card">
                    <span>Nbre de Reservations</span>
                    <h3>{{ reservationCount }}</h3>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-12">
            <div class="white_card card_height_100 mb_30 " style="
    margin-right: -255px;
">
              <div class="row">
                <div class="col-lg-9">
                  <div class="white_card_header">
                    <div class="box_header m-0">
                      <div class="main-title">
                        <h3 class="m-0">Liste des Utilisateurs</h3>

                      </div>
                    </div>
                  </div>
                  <div class="white_card_body QA_section">
                    <div class="QA_table " style="
    margin-right: -314px;
">
                      <!-- table-responsive -->
                      <table class="table lms_table_active2 p-0">
                        <thead>
                          <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Email</th>
                            <th scope="col">role</th>
                            <th scope="col">Télèphone</th>
                            <th scope="col">Statut</th>

                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="user in filteredUsers" :key="user.id">
                            <td>
                              <div class="customer d-flex align-items-center">

                                <div class="ml_18">
                                  <h3 class="f_s_18 f_w_900 mb-0">{{ user.name }}</h3>

                                </div>
                              </div>
                            </td>
                            <td>
                              <div>
                                <h3 class="f_s_18 f_w_900 mb-0">{{ user.email }}</h3>
                                <!-- <span class="f_s_12 f_w_700 color_text_3">12.12.2022</span> -->
                              </div>
                            </td>
                            <td class="f_s_14 f_w_400 color_text_3">
                              <!-- Si le rôle est "coach" -->
                              <a v-if="user.role === 'coach'" href="#" class="badge_active4">Coach</a>
                              <!-- Si le rôle est "startup" -->
                              <a v-else-if="user.role === 'startup'" href="#" class="badge_active">Startup</a>
                              <!-- Si le rôle est "investisseur" -->
                              <a v-else-if="user.role === 'investisseur'" href="#"
                                class="badge_active2">Investisseur</a>
                              <!-- Sinon, un statut par défaut -->
                              <a v-else href="#" class="badge_active3">Admin</a>
                            </td>

                            <td>
                              <div>
                                <h3 class="f_s_18 f_w_800 mb-0">{{ user.phone_number }}</h3>
                                <!-- <span class="f_s_12 f_w_500 color_text_3">Fashion and design</span> -->
                              </div>
                            </td>
                            <td class="f_s_14 f_w_400 color_text_3">
                              <a v-if="user.statut === 'active'" href="#" class="badge_active">Active</a>
                              <a v-else href="#" class="badge_active3">Inactive</a>
                            </td>
                            <td>
                              <div class="action_btns d-flex">
                                <a href="#" class="action_btn mr_10"> <i class="far fa-edit"></i> </a>
                                <a href="#" class="action_btn"> <i class="fas fa-trash"></i> </a>
                              </div>
                            </td>
                          </tr>


                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>




        </div>
      </div>
    </div>
  </Main>
</template>
<style></style>