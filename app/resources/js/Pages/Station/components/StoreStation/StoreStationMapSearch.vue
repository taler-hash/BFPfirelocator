<template>
    <div class="relative">
        <div v-if="isLoading" class="absolute w-full h-full grid place-items-center bg-gray-200/50">
            <ProgressSpinner class="!w-5 !h-5" strokeWidth="8"/>
        </div>
        <InputText placeholder="Search" v-model="filters.q" @input="handleSearch" class="!w-48" />
        <div v-if="data.length" class="flex bg-white rounded shadow-sm max-h-[100px] overflow-y-auto cursor-pointer">
            <div class="">
                <div v-for="location in data" class="px-4 p-2 hover:bg-gray-200 text-[10px] border-b" @click="selectLocation(location)">
                    {{ location.display_name }}
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import axios from 'axios';
import InputText from 'primevue/inputtext';
import { ref, inject, Ref } from 'vue';
import { useDebounceFn } from '@vueuse/core';
import ProgressSpinner from 'primevue/progressspinner';
import { SetViewKeyType, ViewTypes } from '../../types/stationTypes';

interface ResultTypes {
    place_id: number;
    licence: string;
    osm_type: string;
    osm_id: number;
    lat: string;
    lon: string;
    class: string;
    type: string;
    place_rank: number;
    importance: number;
    addresstype: string;
    name: string;
    display_name: string;
    address: Address;
    boundingbox: string[];
}

interface Address {
    city: string;
    region: string;
    "ISO3166-2-lvl3": string;
    postcode: string;
    country: string;
    country_code: string;
}

interface FilterTypes {
    q: string,
    limit: number,
    format: string,
    addressdetails: number
}

const filters = ref<FilterTypes>({
    q: '',
    limit: 20,
    format: 'json',
    addressdetails: 1
})

const isLoading = ref<boolean>(false)
const setViewKey: SetViewKeyType = Symbol()
const setView = inject<any>('setView');

const data = ref<ResultTypes[]>([]);

const handleSearch = useDebounceFn(() => {
    get()
}, 1000)

async function get() {
    isLoading.value = true
    const res = await axios.get('https://nominatim.openstreetmap.org/search', { params: filters.value })

    if (res.status === 200) {
        isLoading.value = false
        data.value = res.data
    }

}

async function selectLocation(e: ResultTypes) {
    console.log(setView)
    if(setView) {
        setView({
            latitude: +e.lat,
            longitude: +e.lon,
            zoom: 16
        })

    }
}

// Tiwasa mag himu kag imuhang search funcion mismo sa https://nominatim.openstreetmap.org/search?q=mandaue&limit=5&format=json&addressdetails=1 mao ni data na isearch
</script>
