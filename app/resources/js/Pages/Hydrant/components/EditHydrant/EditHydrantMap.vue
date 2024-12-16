<template>
    <div class="w-full h-full relative">
        <div class="absolute top-2 right-2 z-[9999] w-48">
            <EditStationMapSearch size="small" />
        </div>
        <div class="w-full h-full" id="map"></div>
    </div>
</template>
<script lang="ts" setup>
import L from 'leaflet';
import { inject, onMounted, provide, ref } from 'vue';
import EditStationMapSearch from './EditHydrantMapSearch.vue';
import { ViewTypes } from '../../types/hydrantTypes';

const map = ref<any>()
const setCoords = inject<any>('setCoords')
const form = inject<any>('form')
const mark = ref<L.Marker>()
const view = ref<ViewTypes>({
    latitude: form.latitude,
    longitude: form.longitude,
    zoom: 17
})

onMounted(() => {
    initMap()
})

function initMap() {
    map.value = L.map('map').setView([view.value.latitude, view.value.longitude], view.value.zoom);

    const tiles = L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map.value);

    mark.value = L.marker([form.latitude, form.longitude]).addTo(map.value)

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

        if(mark.value) {
            map.value.removeLayer(mark.value)
        }

        mark.value = L.marker(e.latlng).addTo(map.value)

        setCoords({
            latitude: e.latlng.lat,
            longitude: e.latlng.lng
        })
    })
}

provide('setView', setView)

</script>