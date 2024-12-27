<template>
    <div class="flex flex-col">
        <div class="space-y-3 pr-2 order-last sm:order-first w-full sm:w-fit pt-4">
            <div class="flex flex-col gap-2">
                <label for="name">Name</label>
                <InputText id="name" v-model="station.name" aria-describedby="name-help" disabled />
            </div>
            <div class="flex flex-col gap-2">
                <label for="location">Location</label>
                <InputText id="location" v-model="station.location" aria-describedby="location-help" disabled />
            </div>
            <div class="flex flex-col gap-2">
                <label for="latitude">Latitude</label>
                <InputNumber disabled id="latitude" v-model="station.latitude" aria-describedby="latitude-help"
                    mode="decimal" :minFractionDigits="2" :useGrouping="false" fluid />
            </div>
            <div class="flex flex-col gap-2">
                <label for="longitude">Longitude</label>
                <InputNumber disabled id="longitude" v-model="station.longitude" aria-describedby="longitude-help"
                    mode="decimal" :minFractionDigits="10" :useGrouping="false" fluid  />
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import { inject } from 'vue';

const station = inject<any>('station')
</script>