<script setup>
import { ref } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import Main from '@/Layouts/Main.vue';

const props = defineProps({
    messages: Object
});

const deleteMessage = (id) => {
    if (confirm("Voulez-vous vraiment supprimer ce message ?")) {
        router.delete(route('admin.support.messages.delete', id));
    }
};
</script>

<template>
    <Main>
        <div class="p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold mb-4">Messages de Contact</h1>

            <table class="w-full border-collapse bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-4">Nom</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Catégorie</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="message in messages.data" :key="message.id" class="border-t">
                        <td class="p-4">{{ message.name }}</td>
                        <td class="p-4">{{ message.email }}</td>
                        <td class="p-4">{{ message.category }}</td>
                        <td class="p-4">{{ new Date(message.created_at).toLocaleDateString() }}</td>
                        <td class="p-4">
                            <Link :href="route('admin.support.message.view', message.id)" class="text-blue-600 hover:underline">
                                Voir
                            </Link>
                            <button @click="deleteMessage(message.id)" class="text-red-600 hover:underline ml-4">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center" v-if="messages && messages.links">
    <template v-for="link in messages.links" :key="link.label">
        <Link v-if="link.url" :href="link.url" v-html="link.label"
              class="px-4 py-2 mx-1 rounded bg-gray-200 hover:bg-gray-300">
        </Link>
    </template>
</div>
        </div>
    </Main>
</template>
