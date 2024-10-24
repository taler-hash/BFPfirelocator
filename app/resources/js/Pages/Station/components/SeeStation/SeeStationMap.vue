<template>
    <div class="relative sm:w-48 sm:h-48 w-full h-48 border rounded-md overflow-hidden">
        <div class="absolute top-0 right-0 w-full h-full z-[9999]"></div>
        <div class="w-full h-full" id="map"></div>
    </div>
</template>
<script setup lang="ts">
import L from 'leaflet';
import { inject, onMounted, ref } from 'vue';

const station = inject<any>('station')
const map = ref<any>()
const mark = ref<L.Marker>()

onMounted(() => {
    initMap()
})

function initMap() {
    map.value = L.map('map').setView([station.value.latitude, station.value.longitude], 17);

    const tiles = L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map.value);

    mark.value = L.marker([station.value.latitude, station.value.longitude]).addTo(map.value)
}
</script>