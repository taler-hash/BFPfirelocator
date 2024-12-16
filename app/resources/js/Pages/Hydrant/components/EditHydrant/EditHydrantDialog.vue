<template>
    <Dialog v-model:visible="visible" modal header="Edit Hydrant" @hide="close">
        <form @submit.prevent="submit">
            <div class="flex flex-col sm:flex-row items-start">
                <div class="space-y-3 pr-2 sm:border-r-2 order-last sm:order-first w-full sm:w-fit">
                    <div class="flex flex-col gap-2">
                        <label for="name">Name</label>
                        <InputText id="name" v-model="form.name" aria-describedby="name-help" required />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="latitude">Latitude</label>
                        <InputNumber disabled id="latitude" v-model="form.latitude" aria-describedby="latitude-help"
                            mode="decimal" :minFractionDigits="2" :useGrouping="false" fluid required />
                        <InputError :message="form.errors.latitude" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="longitude">Longitude</label>
                        <InputNumber disabled id="longitude" v-model="form.longitude" aria-describedby="longitude-help"
                            mode="decimal" :minFractionDigits="10" :useGrouping="false" fluid required />
                        <InputError :message="form.errors.longitude" />
                    </div>
                    <div class="pt-2">
                        <Button type="submit" severity="success" label="submit" class="w-full" :disabled="!form.isDirty" />
                    </div>
                </div>
                <div class="ml-2 w-[320px] border overflow-hidden mt-4 sm:mt-0">
                    <div class="w-full h-96">
                        <EditHydrantMap />
                    </div>
                </div>
            </div>
        </form>
    </Dialog>
</template>

<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { inject, provide, ref } from 'vue';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { HydrantTypes } from '../../types/hydrantTypes';
import EditHydrantMap from './EditHydrantMap.vue';
import { useToast } from 'primevue/usetoast';

const reloadTable = inject<any>('reloadTable')
const toast = useToast()
const visible = ref<boolean>(false)

const form = useForm<HydrantTypes>({
    name: '',
    longitude: undefined,
    latitude: undefined
})

function open(Hydrant: HydrantTypes) {
    form.defaults(Hydrant)
    form.reset()
    visible.value = true
}

function close() {
    visible.value = false
    form.clearErrors()
}

function setCoords(values: { longitude: number, latitude: number }) {
    form.latitude = values.latitude
    form.longitude = values.longitude
}

async function submit() {
    form.put(route('hydrants.edit', form.id), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Updated Hydrant Successfully', life: 3000 });
            close()
            form.reset()
            reloadTable()
        }
    })
}

provide('setCoords', setCoords)
provide('form', form)

defineExpose({
    open
})
</script>