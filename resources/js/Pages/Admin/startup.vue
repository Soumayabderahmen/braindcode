<script setup>
import Main from "@/Layouts/main.vue";
import { Head } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

defineProps({
  startups: Array,
});

const page = usePage();

const activateStartup = (startupId) => {
    window.location.href = `/admin/activate-startup/${startupId}`;
};
</script>

<template>
  <Head title="Dashboard" />

  <Main :showSidebar="true">
    <div class="container mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Gestion des startups</h1>

      <table class="table-auto w-full border-collapse border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2">Nom</th>
            <th class="border p-2">Email</th>
            <th class="border p-2">Domaine</th>
            <th class="border p-2">Statut</th>
            <th class="border p-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="startup in startups"
            :key="startup.id"
           :class="startup.statut === 'active' ? 'bg-green-100' : 'bg-red-100'"
            class="border border-gray-300"
          >
            <td class="border border-gray-300 px-4 py-2">{{ startup.name }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ startup.email }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ startup.domain_name }}</td>
            <span :class="startup.statut === 'active' ? 'text-green-700' : 'text-red-700'" class="font-semibold">
                {{ startup.statut }}
              </span>
            <td class="border p-2">
                        <button 
                            v-if="startup.statut !== 'active'"
                            @click="activateStartup(startup.id)"
                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
                        >
                            Activer
                        </button>
                        <span v-else class="text-gray-500">Activé</span>
                    </td>
          </tr>
        </tbody>
      </table>
    </div>
  </Main>
</template>
