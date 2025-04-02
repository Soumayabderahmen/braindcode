<script setup>
import { ref, defineProps } from "vue";
import Main from "../../Layouts/main.vue";

const props = defineProps({
  startup: Object,
  tabs: {
    type: Array,
    default: () => [
      { name: "POSTS", url: "https://www.bootdey.com/snippets/view/bs4-profile-with-timeline-posts" },
      { name: "ABOUT", url: "https://www.bootdey.com/snippets/view/bs4-profile-about" },
      { name: "PHOTOS", url: "https://www.bootdey.com/snippets/view/profile-photos" },
      { name: "VIDEOS", url: "https://www.bootdey.com/snippets/view/profile-videos" },
      { name: "FRIENDS", url: "https://www.bootdey.com/snippets/view/bs4-profile-friend-list" },
    ],
  },
});

const activeTab = ref("FRIENDS");
</script>

<template>
  <Main :showSidebar="true">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div id="content" class="content content-full-width">
            <div class="profile">
              <!-- Section de couverture -->
              <div class="profile-header">
                <!-- Image de couverture -->
                <div class="profile-header-cover">
                  <img
                    :src="startup.startup?.cover_image ? `/storage/${startup.startup.cover_image}` : 'https://bootdey.com/img/Content/avatar/avatar3.png'"
                    alt="Cover Image"
                    class="cover-image"
                  />
                </div>

                <div class="profile-header-content">
                  <!-- Image du startup -->
                  <div class="profile-header-img">
                    <img
                      :src="startup.startup?.logo_startup ? `/storage/${startup.startup.logo_startup}` : 'https://bootdey.com/img/Content/avatar/avatar3.png'"
                      alt="startup Avatar"
                      class="avatar-image"
                    />
                  </div>

                  <!-- Informations du startup -->
                  <div class="profile-header-info">
                    <h4 class="m-t-12 m-b-10">{{ startup.name }}</h4>
                    <h4 class="m-b-10">Domaine :{{ startup.domain_name }} </h4>
                  </div>
                </div>

                <!-- Onglets -->
                <ul class="profile-header-tab nav nav-tabs">
                  <li class="nav-item" v-for="tab in tabs" :key="tab.name">
                    <a
                      :href="tab.url"
                      target="__blank"
                      class="nav-link"
                      :class="{ 'active show': activeTab === tab.name }"
                      @click.prevent="activeTab = tab.name"
                    >
                      {{ tab.name }}
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Main>
</template>

<style scoped>
.profile-header {
  position: relative;
  overflow: hidden;
  margin-bottom: 20px;
}

.profile-header-cover {
  width: 100%;
  height: 200px; /* Ajuste la hauteur de la couverture */
  position: relative;
  overflow: hidden;
}

.cover-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.profile-header-content {
  color: #fff;
  padding: 25px;
  position: relative;
  z-index: 2;
}

.profile-header-img {
  float: left;
  width: 120px;
  height: 120px;
  overflow: hidden;
  position: relative;
  z-index: 10;
  margin: 0 0 -20px;
  padding: 3px;
  border-radius: 4px;
  background: #626466;
  border: -8px solid #fff;
}

.avatar-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

.profile-header-info h4 {
  font-weight: 500;
  color: black;
}

.profile-header-img + .profile-header-info {
  margin-left: 140px;
  color: black;

}

.profile-header .profile-header-tab {
  background: dimgrey;
  list-style-type: none;
  margin: -10px 0 0;
  padding: 0 0 0 140px;
  white-space: nowrap;
  border-radius: 0;
}

.profile-header .profile-header-tab>li {
  display: inline-block;
  margin: 0;
}

.profile-header .profile-header-tab>li>a {
  display: block;
  color: #929ba1;
  line-height: 20px;
  padding: 10px 20px;
  text-decoration: none;
  font-weight: 700;
  font-size: 12px;
  border: none;
}

.profile-header .profile-header-tab>li.active>a,
.profile-header .profile-header-tab>li>a.active {
  color: #242a30;
}
</style>
