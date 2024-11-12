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
                <UserCards />
                <BookingCard />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import UserCards from '@/Components/Dashboard/UserCards.vue';
import BookingCard from '@/Components/Dashboard/BookingCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>
