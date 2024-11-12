<template>
    <div class="relative w-full h-full border rounded-md overflow-hidden">
        <div class="w-full h-full" id="map"></div>
    </div>
</template>
<script setup lang="ts">
import L from 'leaflet';
import { defineModel, inject, onMounted, ref } from 'vue';

const map = ref<any>()
const mark = ref<L.Marker>()
const latitude = defineModel('latitude')
const longitude = defineModel('longitude')

onMounted(() => {
    initMap()
})

function initMap() {
    map.value = L.map('map').setView([latitude.value as number, longitude.value as number], 17);

    L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map.value);

    mark.value = L.marker([latitude.value as number, longitude.value as number]).addTo(map.value)
}
</script>