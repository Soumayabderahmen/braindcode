<script setup>
import Main from "@/Layouts/Main.vue";
import { Head } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

defineProps({
  coaches: Array,
  investisseurs: Array,
  startups: Array,

});

const page = usePage();


</script>

<template>
  <Head title="Dashboard" />

  <Main :showSidebar="true">
    <div class="container mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Gestion des Coachs</h1>

      <table class="table-auto w-full border-collapse border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2">Nom</th>
            <th class="border p-2">Email</th>
            <th class="border p-2">Statut</th>
            <th class="border p-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="coach in coaches"
            :key="coach.id"
            :class="coach.statut === 'active' ? 'bg-green-100' : 'bg-red-100'"
            class="border border-gray-300"
          >
            <td class="border border-gray-300 px-4 py-2">{{ coach.name }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ coach.email }}</td>
            <td class="border border-gray-300 px-4 py-2">
              <span
                :class="coach.statut === 'active' ? 'text-green-700' : 'text-red-700'"
                class="font-semibold"
              >
                {{ coach.statut }}
              </span>
            </td>
          
          </tr>
        </tbody>
      </table>
    </div>


    <div class="container mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Gestion des investisseurs</h1>

      <table class="table-auto w-full border-collapse border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2">Nom</th>
            <th class="border p-2">Email</th>
            <th class="border p-2">🔐Visibilité</th>

          </tr>
        </thead>
        <tbody>
          <tr
            v-for="investisseur in investisseurs"
            :key="investisseur.id"
           
          >
            <td class="border border-gray-300 px-4 py-2">{{ investisseur.name }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ investisseur.email }}</td>
            <td class="border border-gray-300 px-4 py-2">
  {{ investisseur.visibility === 'public' ? '🔐 Private' : '🔓 Public' }}
</td>

              
            
            
          </tr>
        </tbody>
      </table>
    </div>
    <div class="container mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Gestion des startups</h1>

      <table class="table-auto w-full border-collapse border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2">Nom</th>
            <th class="border p-2">Email</th>
            <th class="border p-2">Domaine</th>

          </tr>
        </thead>
        <tbody>
          <tr
            v-for="startup in startups"
            :key="startup.id"
           
          >
            <td class="border border-gray-300 px-4 py-2">{{ startup.name }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ startup.email }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ startup.domain_name }}
              
            </td>
            
          </tr>
        </tbody>
      </table>
    </div>
  </Main>
</template>