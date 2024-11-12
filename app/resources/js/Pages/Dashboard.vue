<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4 w-full place-content-center">
                    <template v-for="card in cards">
                        <div v-if="card.show" class="bg-white w-full p-4 border rounded-lg shadow-sm ">
                            <div class="flex items-center justify-between pb-4">
                                <p class="font-black text-2xl">{{ card.name }}</p>
                                <span class="text-2xl" :class="card.icon"></span>
                            </div>
                            <div class="">
                                <p class="text-7xl font-black">{{ card?.count ?? '-' }}</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

interface CardProps {
    name: string,
    icon: string,
    count?: number,
    show: boolean
}

const cards = ref<CardProps[]>([
    {
        name: 'Admin Staff',
        icon: 'pi pi-user',
        count: 10,
        show: true
    },
    {
        name: 'Brgy Staff',
        icon: 'pi pi-user',
        count: 10,
        show: true
    },
    {
        name: 'Responders',
        icon: 'pi pi-user',
        count: 10,
        show: true
    },
    {
        name: 'Bookings',
        icon: 'pi pi-user',
        count: 10,
        show: true
    }
])
</script>