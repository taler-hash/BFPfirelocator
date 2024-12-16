<template>
    <div class="flex flex-col">
        <div class="space-y-3 pr-2 order-last sm:order-first w-full sm:w-fit pt-4">
            <div class="flex flex-col gap-2">
                <label for="name">Name</label>
                <InputText id="name" v-model="hydrant.name" aria-describedby="name-help" disabled />
            </div>
            <div class="flex flex-col gap-2">
                <label for="latitude">Latitude</label>
                <InputNumber disabled id="latitude" v-model="hydrant.latitude" aria-describedby="latitude-help"
                    mode="decimal" :minFractionDigits="2" :useGrouping="false" fluid />
            </div>
            <div class="flex flex-col gap-2">
                <label for="longitude">Longitude</label>
                <InputNumber disabled id="longitude" v-model="hydrant.longitude" aria-describedby="longitude-help"
                    mode="decimal" :minFractionDigits="10" :useGrouping="false" fluid  />
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import { inject } from 'vue';

const hydrant = inject<any>('hydrant')

</script>