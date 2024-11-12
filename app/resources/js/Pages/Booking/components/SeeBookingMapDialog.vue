<template>
    <Dialog v-model:visible="visible" modal  :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <template #header>
            <div class="flex items-center space-x-2 font-medium text-xl">
                <p>Booking</p>
                <p class="font-bold">{{ booking?.location_name }}</p>
                <Badge :class="statusSeverity(booking?.status)" :value="booking?.status"/>
            </div>
        </template>
        <div class="w-[calc(100vw-5rem)] h-[calc(100vh-15rem)]">
            <ResponderBookingMap 
                :isResponder="false"
                :booking="booking"
                @cancelled="handleCancelBooking"
                @complete="handleCompleteBooking"/>
        </div>
    </Dialog>
</template>
<script setup lang="ts">
import Badge from 'primevue/badge';
import axios from 'axios';
import ResponderBookingMap from './ResponderBooking/components/ResponderBookingMap.vue';
import Dialog from 'primevue/dialog';
import { inject, ref } from 'vue';
import { BookingTypes } from '../types/BookingTypes';
import { useToast } from 'primevue/usetoast';

const statusSeverity = inject<any>('statusSeverity');
const toast = useToast()
const reloadTable = inject<any>('reloadTable')
const visible = ref<boolean>(false)
const booking = ref<BookingTypes>()

function open(booking: BookingTypes) {
    getBooking(booking)
}

function close() {
    visible.value = false
}

function handleCompleteBooking() {
    close()
    reloadTable()
    toast.add({ severity: 'success', summary: 'Success', detail: 'Booking success', life: 3000 });
}

function handleCancelBooking() {
    close()
    reloadTable()
    toast.add({ severity: 'error', summary: 'Cancelled', detail: 'Booking cancelled', life: 3000 });
}

function getBooking(_booking: BookingTypes) {
    axios.get(route('bookings.show'), { params: _booking})
    .then((res) => {
        if(res.status === 200) {
            booking.value = res.data
            visible.value = true
        }
    })
    .catch((err) => {
        if(err.status === 422) {
            close()
            reloadTable()
            toast.add({ severity: 'error', summary: 'Error', detail: 'Record is outdated. Record will refreshed', life: 3000 });
        }
    })
}

defineExpose({
    open
})

</script>