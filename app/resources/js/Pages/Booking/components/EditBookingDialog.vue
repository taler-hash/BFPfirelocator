<template>
    <Dialog v-model:visible="visible" modal header="Edit Booking" @hide="close">
        <form @submit.prevent="submit">
            <div class="flex flex-col sm:flex-row items-start">
                <div class="space-y-3 pr-2 sm:border-r-2 order-last sm:order-first w-full sm:w-fit">
                    <div class="flex flex-col gap-2">
                        <label for="status">Status</label>
                        <Select id="status" v-model="form.status" :options="status" aria-describedby="status-help"
                            :disabled="bookingProps.role !== 'admin_staff'" />
                        <InputError :message="form.errors.location_name" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="location">Location</label>
                        <InputText id="location" v-model="form.location_name" aria-describedby="location-help"
                            :disabled="form.status !== 'pending' || bookingProps.role !== 'brgy_staff'" />
                        <InputError :message="form.errors.location_name" />
                    </div>
                    <div v-if="bookingProps.role === 'admin_staff'" class="flex flex-col gap-2">
                        <label for="station">Station</label>
                        <StationSelect v-model="form.station_id" />
                        <InputError :message="form.errors.station_id" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="latitude">Latitude</label>
                        <InputNumber disabled id="latitude" v-model="form.latitude" aria-describedby="latitude-help"
                            mode="decimal" :minFractionDigits="2" :useGrouping="false" fluid />
                        <InputError :message="form.errors.latitude" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="longitude">Longitude</label>
                        <InputNumber disabled id="longitude" v-model="form.longitude" aria-describedby="longitude-help"
                            mode="decimal" :minFractionDigits="10" :useGrouping="false" fluid />
                        <InputError :message="form.errors.longitude" />
                    </div>
                    <div v-if="bookingProps.role !== 'brgy_staff'" class="flex flex-col gap-2">
                        <label for="longitude">Responders</label>
                        <Listbox v-model="bookingResponders" disabled :options="bookingResponders" optionLabel="name"
                            class="w-full md:w-56" striped />
                    </div>
                    <div class="pt-2">
                        <Button type="submit" severity="success" label="submit" class="w-full"
                            :disabled="!form.isDirty" />
                    </div>
                </div>
                <div class="ml-2 w-[320px] border overflow-hidden mt-4 sm:mt-0">
                    <div class="w-full h-96">
                        <GetCoordsMap v-if="bookingProps.role === 'brgy_staff'" v-model:latitude="form.latitude"
                            v-model:longitude="form.longitude" />
                        <SeeMap v-else v-model:latitude="form.latitude" v-model:longitude="form.longitude" />
                    </div>
                </div>
            </div>
        </form>
    </Dialog>
</template>
<script setup lang="ts">
import StationSelect from '@/Pages/User/components/StationSelect.vue';
import Select from 'primevue/select';
import Listbox from 'primevue/listbox';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import InputError from '@/Components/InputError.vue';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { inject, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { BookingFormTypes, BookingTypes } from '../types/BookingTypes';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';
import { UserTypes } from '@/Pages/User/types/UserTypes';
import SeeMap from '@/Components/SeeMap/SeeMap.vue';
import GetCoordsMap from '@/Components/GetCoordsMap/GetCoordsMap.vue';

interface BookingResponderTypes {
    booking_id: number,
    name: string,
    user: UserTypes
}
const status = ref<string[]>(['accepted', 'pending'])
const bookingResponders = ref<BookingResponderTypes[]>()
const reloadTable = inject<any>('reloadTable')
const toast = useToast()
const visible = ref<boolean>(false)
const form = useForm<BookingFormTypes>({
    latitude: 0,
    longitude: 0
})
const bookingProps = inject<any>('bookingProps')

async function open(booking: BookingTypes) {
    form.defaults(booking)
    form.reset()
    showBooking()
}

function close() {
    visible.value = false
    form.reset()
}

async function showBooking() {
    axios.get(route('bookings.show'), {
        params: form.data()
    })
        .then((res) => {
            form.defaults(res.data)
            form.reset()
            showBookingResponders()
            showStatusOptions()
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

async function showBookingResponders() {
    const res = await axios.get(route('bookingresponders.index'), {
        params: {
            bookingId: form.id
        }
    })

    if (res.status === 200) {
        bookingResponders.value = res.data.map((v: any) => {
            v.disabled = true
            return v
        })
        showStatusOptions()
    }
}

function showStatusOptions() {
    let _status = ['accepted', 'pending']

    if (form.responders?.length !== 0) {
        _status.unshift('ongoing')
    }

    if (form.status === 'cancelled') {
        _status.push('cancelled')
        _status = _status.filter(s => (!['ongoing'].includes(s)))
    }

    status.value = _status
}

function submit() {
    form.put(route('bookings.edit'), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Updated Booking Successfully', life: 3000 });
            close()
            form.reset()
        },
        onError: (err) => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Record is outdated. Record will refreshed', life: 3000 });
            close()
        },
        onFinish: () => {
            reloadTable()
        }
    })
}

defineExpose({
    open
})
</script>