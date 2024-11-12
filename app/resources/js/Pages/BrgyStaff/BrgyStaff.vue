<template>

    <Head title="Brgy Staff" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Brgy Staff
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <UserList role="brgy_staff" header="Brgy Staff" v-bind="userListProps" />
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
import { onBeforeMount, onMounted, ref } from 'vue';

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
            stationId: page.props.auth.user.station.id,
            crud: true
        }
    }
})

</script>
