<template>
    <Dialog modal v-model:visible="visible" header="Assign a fire fighter" @hide="close">
        <form @submit.prevent="submit">
            <div class="flex flex-col sm:flex-row items-start">
                <div class="space-y-3 pr-2 sm:border-r-2 order-last sm:order-first w-full sm:w-fit">
                    <div class="flex flex-col gap-2">
                        <label for="location">Location</label>
                        <InputText id="location" v-model="form.location_name" aria-describedby="location-help"
                            disabled />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="latitude">Latitude</label>
                        <InputNumber disabled id="latitude" v-model="form.latitude" aria-describedby="latitude-help"
                            mode="decimal" :minFractionDigits="2" :useGrouping="false" fluid />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="longitude">Longitude</label>
                        <InputNumber disabled id="longitude" v-model="form.longitude"
                            aria-describedby="longitude-help" mode="decimal" :minFractionDigits="10"
                            :useGrouping="false" fluid />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="longitude">Responders</label>
                        <MultiSelect 
                            v-model="form.responders" 
                            display="chip" 
                            :options="responders" 
                            optionLabel="name" 
                            :optionDisabled="((option) => option.status === 'not_available')"
                            filter
                            placeholder="Select Responders" 
                            :maxSelectedLabels="3" 
                            class="w-full md:w-80">
                            <template #option="slotProps">
                                <div class="flex items-center space-x-2">
                                    <p>{{ slotProps.option.name }}</p> <Badge v-if="slotProps.option?.status" severity="danger" value="Assigned"/>
                                </div>
                            </template>
                        </MultiSelect>
                    </div>
                    <div class="pt-2">
                        <Button :disabled="!form.isDirty" type="submit" severity="success" label="submit" class="w-full" />
                    </div>
                </div>
                <div class="ml-2 w-[320px] border overflow-hidden mt-4 sm:mt-0">
                    <div class="w-full h-96">
                        <SeeMap v-model:latitude="form.latitude" v-model:longitude="form.longitude" />
                    </div>
                </div>
            </div>
        </form>
    </Dialog>
</template>
<script setup lang="ts">
import Badge from 'primevue/badge';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { inject, ref, useCssVars } from 'vue';
import { BookingTypes } from '../../types/BookingTypes';
import axios from 'axios';
import { UserTypes } from '@/Pages/User/types/UserTypes';
import { FilterTypes } from '@/Pages/Station/types/stationTypes';
import SeeMap from '@/Components/SeeMap/SeeMap.vue';
import { useForm } from '@inertiajs/vue3';
import MultiSelect from 'primevue/multiselect';
import { useToast } from 'primevue/usetoast';

const reloadTable = inject<any>('reloadTable')
const toast = useToast()
const bookingProps = inject<any>('bookingProps')

interface FilterWithRoleAndStation extends FilterTypes {
    role: string,
    stationId: number,
    bookingId: number
}

// tiwasa mag himug kag fetch para sa booking responders multiselect gamita
const booking = ref<BookingTypes>({
    location_name: '',
    latitude: 0,
    longitude: 0
})

const form = useForm<BookingTypes>({
    responders: [],
})
const hasAssignedResponders = ref<boolean>(false)
const responders = ref<UserTypes[]>()
const visible = ref<boolean>(false)
const filters = ref<FilterWithRoleAndStation>({
    page: 1,
    sortBy: 'id',
    sortType: 'desc',
    rows: 10,
    searchString: '',
    role: 'responder',
    stationId: bookingProps.stationId ?? 0,
    bookingId: 0
})

function open(booking: BookingTypes) {
    form.defaults(booking)
    form.reset()
    showBooking()
}

// tiwasa ni mag buhat kag logic para sa updating sa mga responders

function close() {
    form.reset()
    visible.value = false
}

function showBooking() {
    axios.get(route('bookings.show'), {
        params: form.data()
    })
    .then((res) => {
        if (res.data.responders.length) {
            hasAssignedResponders.value = true
            filters.value.bookingId = res.data.id
            form.defaults(res.data)
            form.reset()
            displayAssignedResponders()
        } else {
            form.defaults({responders: null})
            form.reset()
            hasAssignedResponders.value = false
        }

        getResponders()
    })
    .catch((err: any) => {
        if(err.status === 422) {
            reloadTable()
            close()
            toast.add({ severity: 'error', summary: 'Error', detail: 'Record is outdated. Record will refreshed', life: 3000 });
        }
    })
}

async function getResponders() {
    const res = await axios.get(route('users.index'), { params: filters.value })

    if (res.status === 200) {
        responders.value = res.data.data
        checkIfResponderIsAssignedInBooking()
        visible.value = true
    }
}

function checkIfResponderIsAssignedInBooking() {
    let currentResponders = form.data().responders

    responders.value?.forEach((responder: any, index: any) => {
        currentResponders?.forEach((curResponder: any, curResIndex: number) => {
            currentResponders[curResIndex].status = null
            if(responder.id === curResponder.id) {
                if(responders.value) {
                    responders.value[index].status = null
                }
            }
        })
    });

    form.defaults({
        responders: currentResponders
    })

    form.reset()
}

function displayAssignedResponders() {
    const responders = form.responders?.map((responder: any) => {

        return responder.user
    })

    form.defaults({
        responders: responders
    })
    form.reset()
}


function submit() {
    if(hasAssignedResponders.value) {
        form.put(route('bookingresponders.bulkEdit'), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Updated Responders Successfully', life: 3000 });
            close()
        },
        onError: (err) => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Record is outdated. Record will refreshed', life: 3000 });
            close()
        },
        onFinish: () => {
            reloadTable()
        }
    })

    } else {
        form.post(route('bookingresponders.store'), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Added Responders Successfully', life: 3000 });
            close()
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
    
}

defineExpose({
    open
})
</script>