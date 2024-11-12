<template>
    <Select v-model="model" :options="stations?.data" optionLabel="name" optionValue="id" class="w-full md:w-56" filter @filter="onFilter"
        placeholder="Select Item" />
</template>
<script setup lang="ts">
import Select from 'primevue/select';
import { StationPaginationTypes } from '@/Pages/Station/types/stationTypes';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterTypes } from '../types/UserTypes';
import { useDebounceFn } from '@vueuse/core';

const model = defineModel<number | null>()
const stations = ref<StationPaginationTypes>()
const filters = ref<FilterTypes>({
    page: 1,
    sortBy: 'id',
    sortType: 'asc',
    rows: 10,
    searchString: ''
})

onMounted(() => {
    fetchStation()
})

const fetchStation = async () => {

    // Update filters if needed (e.g., page number based on first)
    // tiwasa mag implement kag lazy load sa select
    const res = await axios.get(route('stations.index'), { params: filters.value });

    if (res.status === 200) {
        // Update stations with fetched data
        stations.value = res.data as StationPaginationTypes;
    }
};

const onFilter = useDebounceFn((e: { value: string}) => {
    filters.value.searchString = e.value
    model.value = null
    fetchStation()
}, 500)

</script>