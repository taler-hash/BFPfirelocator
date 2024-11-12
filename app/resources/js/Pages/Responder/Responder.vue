<template>

    <Head title="Responder" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Responders
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <UserList role="responder" header="Responder" v-bind="userListProps" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserList from '../User/UserList.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { onBeforeMount, ref } from 'vue';

const userListProps = ref<any>()
const page = usePage<any>()

onBeforeMount(() => {
    if(page.props.auth.user.role === 'admin') {
        userListProps.value = {
            filterStation: true,
            crud: true
        }
    } else {
        userListProps.value = {
            'station-id': page.props.auth.user.station.id,
            crud: true
        }
    }
})

</script>
