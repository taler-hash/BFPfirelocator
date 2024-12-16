<template>
    <div class="relative w-full h-full">
        <BookingResponderMapDetail v-if="props.isResponder"/>
        <div class="w-full h-full" id="map"></div>
    </div>
</template>
<script setup lang="ts">
import L from 'leaflet'
import { ViewTypes } from '@/Pages/User/types/UserTypes';
import { onBeforeUnmount, onMounted, provide, ref, watch } from 'vue';
import BookingResponderMapDetail from './ResponderBookingMapDetail.vue';
import { FireIcon, StationIcon, FireFightericon, HydrantIcon } from '@/Api/Icons';
import 'leaflet-routing-machine'
import axios from 'axios';
import { useIntervalFn } from '@vueuse/core';
import { HydrantTypes } from '@/Pages/Hydrant/types/hydrantTypes';

const emit = defineEmits(['complete', 'cancelled'])
const { pause } = useIntervalFn(() => {getCurrentPosition()}, 2000) 
const props = defineProps(['booking', 'isResponder'])
const map = ref<any>()
const view = ref<ViewTypes>({
    latitude: 10.45,
    longitude: 123.85,
    zoom: 9
})
const currentPosition = ref<{ latitude: number, longitude: number, marker?: any }>({
    latitude: 0,
    longitude: 0,
    marker: undefined
})
const watchId = ref<any>()
const hydrants = ref<HydrantTypes[]>([])

onMounted(() => {
    initMap()
    markEndPoint()
    marStationPoint()
    addRoutePath()
    !props.isResponder && pause()
    initLive()
    getHydrants()
})

onBeforeUnmount(() => {
    //@ts-ignore
    window.Echo.leave(`booking.${props.booking.id}`)
})

function initMap() {
    map.value = L.map('map').setView([view.value.latitude, view.value.longitude], view.value.zoom);

    L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map.value);
}

function markEndPoint() {
    const { latitude, longitude } = props.booking
    L.marker([latitude, longitude], { icon: FireIcon}).addTo(map.value)
}

function marStationPoint() {
    const { latitude, longitude } = props.booking.station
    L.marker([latitude, longitude], { icon: StationIcon}).addTo(map.value)
}

// tiwasa ang current point
function setCurrentPoint(n: any) {
    if(currentPosition.value.marker) {
        map.value.removeLayer(currentPosition.value.marker)
        currentPosition.value = {
            ...currentPosition.value,
            marker: undefined
        }
    }
    const { latitude, longitude } = n.coords
    currentPosition.value = {
        latitude: latitude,
        longitude: longitude,
        marker: L.marker([latitude, longitude], { icon: FireFightericon}).addTo(map.value)}
}

function addRoutePath() {
    const booking = props.booking
    const station = props.booking.station

    //@ts-ignore
    L.Routing.control({
        waypoints: [
            L.latLng(booking.latitude, booking.longitude),
            L.latLng(station.latitude, station.longitude)
        ],
        addWaypoints: false,
        createMarker: () => null,
    }).addTo(map.value)

}

function success(position: any) {
    const { latitude , longitude } = position.coords

    sendCoords({latitude: latitude, longitude: longitude}) 
}

function sendCoords(coords: { latitude: number, longitude: number}) {
    axios.post(route('bookings.sendCoords'), {
        booking: props.booking,
        coords: coords
    })
}

function error(error: any) {
    if(watchId.value) {
        navigator.geolocation.clearWatch(watchId.value)
        getCurrentPosition()
    }
}

function getHydrants() {
    const filters = {
        all: true
    }

    axios.get(route('hydrants.index'), {params: filters})
    .then((res) => {
        hydrants.value = res.data

        markHydrants()
    })
    .catch(err => {
        console.error(err)
    })
}

function markHydrants() {
    hydrants.value.map((item) => {
        const { longitude, latitude } = item
        L.marker([latitude, longitude], { icon: HydrantIcon}).addTo(map.value)
    })
}

const options = {
  enableHighAccuracy: true,
  maximumAge: 2000,
  timeout: 7000,
}

function getCurrentPosition() {
    watchId.value = navigator.geolocation.getCurrentPosition(success, error);
}

function initLive() {
    //@ts-ignore
    window.Echo.join(`booking.${props.booking.id}`)
    .listen('BookingMapUpdatedEvent', (event:any) => {
        event.coords && setCurrentPoint(event)
        trackStatus(event)

    })
    .leaving((user: any) => {
        if(user.role === 'responder') {
            const payload = {
                user: user,
                booking: props.booking
            }
            axios.post(route('bookings.unsetResponderCoords'), payload)
        }
    });
}

function trackStatus(event: any) {
    const status = event.booking.status
    if(status === 'completed') {
        handleCompleteBooking()
    } else if (status === 'cancelled') {
        handleCancelBooking()
    }
}

function handleCompleteBooking() {
    emit('complete')
}

function handleCancelBooking() {
    emit('cancelled')
}

provide('bookingProps', props)

</script>