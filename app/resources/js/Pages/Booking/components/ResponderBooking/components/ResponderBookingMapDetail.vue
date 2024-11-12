<template>
    <div class="absolute left-1/2 transform -translate-x-1/2 bottom-2 w-[15rem] h-[7rem] bg-white !z-[99999] rounded-md shadow-sm border-2 border-orange-500">
        <div class="p-2">
            <div class="">
                <p class="text-center">{{ bookingProps.booking.location_name }}</p>
                <div class="flex space-x-2 items-center justify-center">
                    <Badge class="!bg-purple-600" :value="bookingProps.booking.status" size="small" />
                    <p class="text-xs text-gray-500">{{ bookingProps.booking.booking_date }}</p>
                </div>  
            </div>
            <div v-if="bookingProps.isResponder" class="p-2 w-full flex justify-center space-x-2">
                <Button label="Complete" severity="success" size="small" @click="handleComplete"/>
                <Button label="Cancel" severity="danger" size="small" @click="handleCancel"/>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import Badge from 'primevue/badge';
import { inject } from 'vue';
import Button from 'primevue/button';
import axios from 'axios';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from 'primevue/usetoast';

const toast = useToast()
const confirm = useConfirm();
const bookingProps = inject<any>('bookingProps')

function handleComplete() {
    confirm.require({
        message: 'Are you sure you want to complete the boooking?',
        header: 'Confirmation',
        accept: () => {
            handleCompleteBooking()
        }
    });
}

function handleCancel() {
    confirm.require({
        message: 'Are you sure you want to cancel the boooking?',
        header: 'Confirmation',
        accept: () => {
            handleCancelBooking()
        }
    });
}

function handleCompleteBooking() {
    let toUpdateBooking = {...bookingProps.booking, status: 'completed', event: true}

    axios.put(route('bookings.edit'), toUpdateBooking)
    toast.add({ severity: 'success', summary: 'Completed', detail: 'Booking was complete', life: 3000 });
}

function handleCancelBooking() {
    let toUpdateBooking = {...bookingProps.booking, status: 'cancelled', event: true}

    axios.put(route('bookings.edit'), toUpdateBooking)
    toast.add({ severity: 'error', summary: 'Cancelled', detail: 'Booking was was cancelled', life: 3000 });
}
</script>