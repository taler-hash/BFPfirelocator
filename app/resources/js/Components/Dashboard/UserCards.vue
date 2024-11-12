<template>
    <div class="grid grid-cols-[repeat(auto-fit,minmax(300px,1fr))] gap-4 w-full justify-items-center">
        <template v-for="(card, index) in cards" :key="index">
            <div v-if="card.show" class="bg-white w-full p-4 border rounded-lg shadow-sm ">
                <div class="flex items-center justify-between pb-4">
                    <p class="font-black text-2xl">{{ card.name }}</p>
                    <span class="text-2xl" :class="card.icon"></span>
                </div>
                <div>
                    <Suspense>
                        <p class="text-7xl font-black">{{ card.count ?? '-' }}</p>
                        <template #fallback>
                            -
                        </template>
                    </Suspense>
                </div>
            </div>
        </template>
    </div>
</template>
<script setup lang="ts">
import { FilterTypes } from '@/Pages/User/types/UserTypes';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

interface CardProps {
    name: string;
    icon: string;
    count?: number;
    show: boolean;
}
interface FilterWithRoleTypes extends FilterTypes {
    role: string;
    stationId: number;
}
const page = usePage<any>()
const filters = ref<FilterWithRoleTypes>({
    page: 1,
    sortBy: 'id',
    sortType: 'desc',
    rows: 10,
    searchString: '',
    role: '',
    stationId: 0,
});

const cards = ref<CardProps[] | []>([
    {
        name: 'Admin Staff',
        icon: 'pi pi-user',
        count: undefined,
        show: ['admin'].includes(page.props.auth.user.role)
    },
    {
        name: 'Brgy Staff',
        icon: 'pi pi-user',
        count: undefined,
        show: ['admin', 'admin_staff'].includes(page.props.auth.user.role)
    },
    {
        name: 'Responders',
        icon: 'pi pi-user',
        count: undefined,
        show: ['admin', 'admin_staff'].includes(page.props.auth.user.role)
    },
]);

async function getUsersCount(role: string) {
    try {
        const stationId = page.props.auth.user.role === 'admin' ? undefined : page.props.auth.user.station_id
        const res = await axios.get(route('users.index'), { params: { ...filters.value, role: role, stationId: stationId } });
        return res.status === 200 ? res.data.total : 0;
    } catch (error) {
        console.error('Error fetching user count:', error);
        return 0;
    }
}

onMounted(async () => {
    const roles = ['admin_staff', 'brgy_staff', 'responder'];
    for (const [index, role] of roles.entries()) {
        cards.value[index].count = await getUsersCount(role);
    }
});

</script>