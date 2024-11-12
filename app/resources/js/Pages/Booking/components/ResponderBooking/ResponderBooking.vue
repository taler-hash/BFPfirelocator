<template>
    <div class="relative w-full h-[calc(100vh-15rem)]">
        <div v-if="isLoading" class="absolute w-full h-full inset-0 grid place-items-center z-[999999] bg-white">
            <ProgressSpinner style="width: 50px; height: 50px" strokeWidth="5" fill="transparent" />
        </div>
        <div v-else class="w-full h-full">
            <div v-if="bookingResponder" class="w-full h-full">
                <ResponderBookingMap 
                    :isResponder="true" 
                    :booking="bookingResponder.booking"
                    @complete="handleCompleteBooking"
                    @cancelled="handleCancelBooking"/>
            </div>
            <div class="w-full h-full grid place-items-center">
                <BookingResponderNoBooking />
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import BookingResponderNoBooking from './ResponderBookingNoBooking.vue';
import ProgressSpinner from 'primevue/progressspinner';
import { onMounted, provide, ref } from 'vue';
import ResponderBookingMap from './components/ResponderBookingMap.vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { BookingResponderTypes } from '../../types/BookingResponderTypes';


const page = usePage()
const bookingResponder = ref<BookingResponderTypes>()
const isLoading = ref<boolean>(true)



onMounted(() => {
    getOngoingBooking()
})

function getOngoingBooking() {
    axios.get(route('bookingresponders.getOnGoingBooking'), {
        params: {
            id: page.props.auth.user.id
        }
    })
        .then((res) => {
            bookingResponder.value = Object.keys(res.data).length > 0 ? res.data : null
        })
        .finally(() => {
            isLoading.value = false
        })
}

function handleCompleteBooking() {
    getOngoingBooking()
}

function handleCancelBooking() {
    getOngoingBooking()
}



provide('bookingResponderProps', bookingResponder)
</script>