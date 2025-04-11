<template>
  <nav :class="['sidebar', { close: isClosed }]">
    <div class="logo_item">
      <i class="bx bx-menu" id="sidebarOpen" @click="toggleSidebar"></i>
      <img src="/images/logo.jpg" alt="Logo">

      Braindcode
      
    </div>
   
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <div class="menu_content">
      <ul class="menu_items">
        <li class="item" v-for="item in menuItems" :key="item.name">
          <Link :href="route(item.route)" class="nav_link">
            <span class="navlink_icon">
              <i :class="item.icon"></i>
            </span>
            <span class="navlink">{{ item.name }}</span>
          </Link>
        </li>
      </ul>

      <div class="bottom_content">
        <div class="bottom expand_sidebar" @click="toggleSidebar" v-if="isClosed">
          <span>Expand</span>
          <i class="bx bx-log-in"></i>
        </div>
        <div class="bottom collapse_sidebar" @click="toggleSidebar" v-else>
          <span>Collapse</span>
          <i class="bx bx-log-out"></i>
        </div>
      </div>
    </div>
  </nav>
</template>
<script setup>
import { computed, ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";

const isClosed = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value && user.value.role === "admin");

// Correction du routage avec `route()`
const menuItems = computed(() => {
  if (isAdmin.value) {
    return [
      { name: "Dashboard", route: "admin.dashboard", icon: "bx bx-home" },
      { name: "Coachs", route: "admin.dashboard", icon: "bx bx-chalkboard" },
      { name: "Investisseurs", route: "admin.dashboard", icon: "bx bx-money" },
      { name: "Startups", route: "admin.dashboard", icon: "bx bx-rocket" },
      { name: "Messages Support", route: "admin.support.messages", icon: "bx bx-envelope" },
      { name: "FAQs", route: "admin.faqs.index", icon: "bx bx-question-mark" },
      { name: "Chatbot IA", route: "admin.chatbot.index", icon: "bx bx-bot" }

    ];
  } else {
    return [
      { name: "Dashboard", route: "dashboard", icon: "bx bx-home" },
      { name: "Profil", route: "profile.edit", icon: "bx bx-user" },
      
    ];
  }
});

const toggleSidebar = () => {
  isClosed.value = !isClosed.value;
};

</script>
<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

:root {
  --white-color: #fff;
  --blue-color: #4070f4;
  --grey-color: #707070;
  --grey-color-light: #aaa;
}

.sidebar {
  width: 260px;
  height: 100vh;
  background: var(--sidebar-bg);
  transition: width 0.3s ease;
  position: fixed;
  top: 0;
  left: 0;
  overflow: hidden;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar.close {
  
  width: 80px;
}

.sidebar::-webkit-scrollbar {
  display: none;
}

.menu_content {
  position: relative;
}

.menu_items {
  padding: 0;
  list-style: none;
}

.navlink_icon {
  position: relative;
  font-size: 22px;
  min-width: 50px;
  line-height: 40px;
  display: inline-block;
  text-align: center;
  border-radius: 6px;
  /* Ensure icons are vertically centered */
  vertical-align: middle;
}

.navlink_icon::before {
  content: "";
  position: absolute;
  height: 100%;
  width: calc(100% + 100px);
  left: -20px;
}

.navlink_icon:hover {
  background: var(--blue-color);
}

.sidebar .nav_link {
  display: flex;
  align-items: center;
  width: 100%;
  padding: 4px 15px;
  border-radius: 8px;
  text-decoration: none;
  color: var(--grey-color);
  white-space: nowrap;
  /* Improve vertical alignment of text */
  align-items: center;
}

.sidebar.close .navlink {
  display: none;
}

.nav_link:hover {
  color: var(--white-color);
  background: var(--blue-color);
}

.sidebar.close .nav_link:hover {
  background: var(--white-color);
}
.bottom_content {
  position: relative;
    bottom: -365px;
    left: -43px;
    width: 100%;
    cursor: pointer;
    transition: all 0.5s ease;
}

.bottom {
  display: flex;
  align-items: center;
  justify-content: center; /* Center horizontally */
  padding: 10px 0; /* Reduced padding */
  text-align: center;
  width: 100%;
  color: var(--grey-color);
  border-top: 1px solid var(--grey-color-light);
  background-color: var(--white-color);
  /* Ensure it sticks to the bottom */
  position: relative;
}

.bottom i {
  font-size: 20px;
  margin-right: 5px; /* Add spacing between icon and text */
}

.bottom span {
  font-size: 16px;
}

.sidebar.close .bottom_content {
  width: 50px;
  left: 15px;
}

.sidebar.close .bottom span {
  display: none;
}



#sidebarOpen {
  display: none;
}

.logo_item {
  display: flex;
  align-items: center;
  column-gap: 24px;
  font-size: 22px;
  font-weight: 500;
  color: var(--blue-color);
  padding: 15px 20px;
}

.logo_item img {
  width: 35px;
  height: 35px;
  border-radius: 50%;
}
.nav_link {
  display: flex;
  align-items: center;
  padding: 10px;
  text-decoration: none;
  color: var(--grey-color);
  transition: background 0.3s ease, box-shadow 0.3s ease;
  border-radius: 8px;
}
.nav_link:hover {
  background: var(--blue-color);
  color: var(--white-color);
  box-shadow: 0px 4px 6px rgba(64, 112, 244, 0.3);
}
</style>

