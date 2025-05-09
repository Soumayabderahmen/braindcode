<script setup>

 
</script>

<template>
  <div class="bg-[#F2F9FF] min-h-screen p-6">
    <!-- Section profil -->
    <div class="relative bg-white rounded-xl shadow p-6">
      <!-- Image background simulée -->
      <img
        src="https://images.unsplash.com/photo-1531497865144-0464ef8fb9a1"
        alt="cover"
        class="w-full h-48 object-cover rounded-t-xl"
      />

      <!-- Avatar & Infos -->
      <div class="flex items-center -mt-10 px-6">
        <img
          src="https://randomuser.me/api/portraits/men/32.jpg"
          alt="avatar"
          class="w-24 h-24 rounded-full border-4 border-white shadow"
        />
        <div class="ml-4">
          <h2 class="text-xl font-bold text-[#1C1C1C]">Youssef Gharbi</h2>
          <p class="text-gray-500 text-sm">500 points 🏅🏅🏅</p>
        </div>
        <div class="ml-auto space-x-3">
          <button class="bg-blue-600 text-white px-4 py-1 rounded shadow hover:bg-blue-700">
            Modifier
          </button>
          <button class="border border-blue-600 text-blue-600 px-4 py-1 rounded shadow hover:bg-blue-50">
            Supprimer
          </button>
        </div>
      </div>

      <!-- Formulaire -->
      <div
        class="mt-8 bg-[#E8F5FF] border-2 border-blue-500 rounded-xl p-6 shadow-sm relative"
      >
        <h3 class="text-blue-600 font-bold mb-6">Modifier Coach</h3>

        <form class="space-y-5">
          <div class="flex gap-4">
            <div class="w-1/2">
              <label class="block font-medium mb-1">Nom</label>
              <input type="text" class="w-full rounded px-4 py-2" placeholder="Écrire ici ..." />
            </div>
            <div class="w-1/2">
              <label class="block font-medium mb-1">Prénom</label>
              <input type="text" class="w-full rounded px-4 py-2" placeholder="Écrire ici ..." />
            </div>
          </div>

          <div>
            <label class="block font-medium mb-1">Email</label>
            <input type="email" class="w-full rounded px-4 py-2" placeholder="Écrire ici ..." />
          </div>

          <div>
            <label class="block font-medium mb-1">Numéro</label>
            <input type="text" class="w-full rounded px-4 py-2" placeholder="Écrire ici ..." />
          </div>

          <div>
            <label class="block font-medium mb-1">Domaine</label>
            <input type="text" class="w-full rounded px-4 py-2" placeholder="Écrire ici ..." />
          </div>

          <div class="flex gap-4">
            <div class="w-1/2">
              <label class="block font-medium mb-1">Adresse</label>
              <input type="text" class="w-full rounded px-4 py-2" placeholder="Écrire ici ..." />
            </div>
            <div class="w-1/2">
              <label class="block font-medium mb-1">Code postal</label>
              <input type="text" class="w-full rounded px-4 py-2" placeholder="Écrire ici ..." />
            </div>
          </div>

          <!-- Bouton -->
          <div class="pt-4">
            <button class="bg-[#0288D1] hover:bg-[#0277BD] text-white px-6 py-2 rounded shadow">
              Sauvegarder
            </button>
          </div>
        </form>

        <!-- Icone poubelle -->
        <button class="absolute top-4 right-4 bg-white p-2 rounded shadow hover:bg-blue-50">
          🗑️
        </button>
      </div>
    </div>
  </div>
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
