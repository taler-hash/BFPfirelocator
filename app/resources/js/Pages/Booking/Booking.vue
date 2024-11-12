<template>

    <Head title="Bookings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Bookings
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <AdminBooking v-if="page.props.auth.user.role === 'admin'"/>
                    <AdminStaffBooking v-if="page.props.auth.user.role === 'admin_staff'"/>
                    <BrgyStaffBooking v-if="page.props.auth.user.role === 'brgy_staff'"/>
                    <ResponderBooking v-if="page.props.auth.user.role === 'responder'"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AdminBooking from './components/AdminBooking.vue'
import AdminStaffBooking from './components/AdminStaffBooking.vue';
import BrgyStaffBooking from './components/BrgyStaffBooking.vue';
import ResponderBooking from './components/ResponderBooking/ResponderBooking.vue';

const page = usePage<any>()
</script>
