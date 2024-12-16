<template>
    <div class="w-full h-full relative">
        <div class="absolute top-2 right-2 z-[9999] w-48">
            <GetCoordsMapSearch size="small" ref="search" />
        </div>
        <div class="w-full h-full" id="map"></div>
    </div>
</template>
<script lang="ts" setup>
import L from 'leaflet';
import { onMounted, provide, ref } from 'vue';
import GetCoordsMapSearch from './GetCoordsMapSearch.vue';
import { ViewTypes } from './types/GetCoordsMapTypes';

const search = ref<any>(null)
const latitude = defineModel<number|undefined>('latitude')
const longitude = defineModel<number|undefined>('longitude')
const map = ref<any>()
const mark = ref<L.Marker>()
const view = ref<ViewTypes>({
    latitude: 10.45,
    longitude: 123.85,
    zoom: 9
})

onMounted(() => {
    initMap()
})

function initMap() {
    map.value = L.map('map').setView([view.value.latitude, view.value.longitude], view.value.zoom);

    L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map.value);

    if (latitude.value && longitude.value) {
        mark.value = L.marker([latitude.value, longitude.value]).addTo(map.value)
        map.value.setView([latitude.value, longitude.value], 17)
    }

    clickEvent()
}

function setView(values: ViewTypes) {
    map.value.setView([values.latitude, values.longitude], values.zoom)
    view.value = {
        latitude: values.latitude,
        longitude: values.longitude,
        zoom: values.zoom
    }
}

function clickEvent() {
    map.value.on('click', function (e: any) {
        search.value.emptySearch()
        if (mark.value) {
            map.value.removeLayer(mark.value)
        }

        mark.value = L.marker(e.latlng).addTo(map.value)

        latitude.value = e.latlng.lat
        longitude.value = e.latlng.lng
    })
}

provide('setView', setView)

</script>