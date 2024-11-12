<template>
    <Dialog v-model:visible="visible" modal header="Book a fire fighter" @hide="close">
        <form @submit.prevent="submit">
            <div class="flex flex-col sm:flex-row items-start">
                <div class="space-y-3 pr-2 sm:border-r-2 order-last sm:order-first w-full sm:w-fit">
                    <div class="flex flex-col gap-2">
                        <label for="location">Location</label>
                        <InputText id="location" v-model="form.location_name" aria-describedby="location-help" required/>
                        <InputError :message="form.errors.location_name" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="latitude">Latitude</label>
                        <InputNumber disabled id="latitude" v-model="form.latitude" aria-describedby="latitude-help"
                            mode="decimal" :minFractionDigits="2" :useGrouping="false" fluid required/>
                        <InputError :message="form.errors.latitude" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="longitude">Longitude</label>
                        <InputNumber disabled id="longitude" v-model="form.longitude" aria-describedby="longitude-help"
                            mode="decimal" :minFractionDigits="10" :useGrouping="false" fluid required/>
                        <InputError :message="form.errors.longitude" />
                    </div>
                    <div class="pt-2">
                        <Button type="submit" severity="success" label="submit" class="w-full" />
                    </div>
                </div>
                <div class="ml-2 w-[320px] border overflow-hidden mt-4 sm:mt-0">
                    <div class="w-full h-96">
                        <GetCoordsMap v-model:latitude="form.latitude" v-model:longitude="form.longitude"/>
                    </div>
                </div>
            </div>
        </form>
    </Dialog>
</template>
<script setup lang="ts">
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import InputError from '@/Components/InputError.vue';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { inject, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { BookingFormTypes } from '../types/BookingTypes';
import GetCoordsMap from '@/Components/GetCoordsMap/GetCoordsMap.vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import moment from 'moment';

const reloadTable = inject<any>('reloadTable')
const toast = useToast()
const page = usePage<any>()
const visible = ref<boolean>(false)
const bookingProps = inject<any>('bookingProps')
const form = useForm<BookingFormTypes>({
    location_name: '',
    latitude: undefined,
    longitude: undefined,
    status: 'pending',
    station_id: page.props.auth.user.station.id,
    booking_date: moment().format('YYYY-MM-DD')
})


function open() {
    visible.value = true
}

function close() {
    visible.value = false
    form.reset()
}

function submit() {
    form.post(route('bookings.store'), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Added Booking Successfully', life: 3000 });
            close()
            form.reset()
            reloadTable()
        }
    })
}

defineExpose({
    open
})
</script>