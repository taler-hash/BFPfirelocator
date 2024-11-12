<template>
    <Dialog v-model:visible="visible" modal header="See Booking" @hide="close">
        <div class="flex flex-col sm:flex-row items-start">
            <div class="space-y-3 pr-2 sm:border-r-2 order-first w-full sm:w-fit">
                <div class="flex flex-col gap-2">
                    <label for="status">Status</label>
                    <InputText id="status" :value="booking?.status" aria-describedby="status-help"
                        disabled />
                </div>
                <div class="flex flex-col gap-2">
                    <label for="location">Location</label>
                    <InputText id="location" :value="booking?.location_name" aria-describedby="location-help"
                        disabled/>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="station">Station</label>
                    <InputText id="status" disabled :value="booking?.station?.name"/>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="latitude">Latitude</label>
                    <InputText disabled id="latitude" :value="booking?.latitude" aria-describedby="latitude-help"
                        mode="decimal" :minFractionDigits="2" :useGrouping="false" fluid />
                </div>
                <div class="flex flex-col gap-2">
                    <label for="longitude">Longitude</label>
                    <InputText disabled id="longitude" :value="booking?.longitude" aria-describedby="longitude-help"
                        mode="decimal" :minFractionDigits="10" :useGrouping="false" fluid/>
                </div>
                <div v-if="bookingProps.role !== 'brgy_staff'" class="flex flex-col gap-2">
                    <label for="longitude">Responders</label>
                    <Listbox v-model="bookingResponders" disabled :options="bookingResponders" optionLabel="name"
                        class="w-full md:w-56" striped />
                </div>
            </div>
            <div class="ml-2 w-[320px] h-[400px] overflow-hidden mt-4 sm:mt-0">
                <p class="font-bold">Logs</p>
                <div class="h-[calc(100%-2rem)] overflow-y-auto overflow-x-hidden">
                    <SeeBookingLogs :logs="bookingLogs"/>
                </div>
            </div>
        </div>
    </Dialog>
</template>
<script setup lang="ts">
import SeeBookingLogs from './SeeBookingLogs.vue';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Listbox from 'primevue/listbox';
import Dialog from 'primevue/dialog';
import { inject, ref } from 'vue';
import { BookingTypes } from '../types/BookingTypes';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { BookingResponderTypes } from '../types/BookingResponderTypes';

const toast = useToast()
const bookingLogs = ref<any>()
const booking = ref<BookingTypes>()
const visible = ref<boolean>(false)
const reloadTable = inject<any>('reloadTable')
const bookingProps = inject<any>('bookingProps')
const bookingResponders = ref<BookingResponderTypes[]>()

function open(props: BookingTypes) {
    console.log(props)
    booking.value = props
    showBooking()
}

function close() {
    visible.value = false
}

async function showBooking() {
    axios.get(route('bookings.show'), {
        params: booking.value
    })
        .then((res) => {
            getBookingLogs()
            showBookingResponders()
            visible.value = true
        })
        .catch((err: any) => {
            if (err.status === 422) {
                reloadTable()
                close()
                toast.add({ severity: 'error', summary: 'Error', detail: 'Record is outdated. Record will refreshed', life: 3000 });
            }
        })
}

async function getBookingLogs() {
    const res = await axios.get(route('bookings.logs'), {params:  booking.value})

    if(res.status === 200) {
        bookingLogs.value = res.data
        console.log(res.data)
    }
}

async function showBookingResponders() {
    const res = await axios.get(route('bookingresponders.index'), {
        params: {
            bookingId: booking.value?.id
        }
    })

    if (res.status === 200) {
        bookingResponders.value = res.data.map((v: any) => {
            v.disabled = true
            return v
        })
    }
}

defineExpose({
    open
})
</script>