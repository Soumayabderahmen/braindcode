<script setup>
import { usePage } from '@inertiajs/vue3';
import Main from "@/Layouts/main.vue";

const props = defineProps({
    coaches: Array
});

const page = usePage();

const activateCoach = (coachId) => {
    window.location.href = `/admin/activate-coach/${coachId}`;
};
</script>

<template>
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
                <tr v-for="coach in props.coaches" :key="coach.id" :class="coach.statut === 'active' ? 'bg-green-100' : 'bg-red-100'"
                class="border border-gray-300">
                    <td class="border p-2">{{ coach.name }}</td>
                    <td class="border p-2">{{ coach.email }}</td>
                    <span
                :class="coach.statut === 'active' ? 'text-green-700' : 'text-red-700'"
                class="font-semibold"
              >
                {{ coach.statut }}
              </span>                    <td class="border p-2">
                        <button 
                            v-if="coach.statut !== 'active'"
                            @click="activateCoach(coach.id)"
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
