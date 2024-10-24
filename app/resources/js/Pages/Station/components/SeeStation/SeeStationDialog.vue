<template>
    <Dialog v-model:visible="visible" modal header="Station" class="w-full sm:w-[calc(100%-4rem)]">
        <div class="flex flex-col sm:flex-row">
            <div>
                <SeeStationMap />
                <SeeStationDetails />
            </div>
            <div class="w-full pt-4 sm:pt-0">
                <SeeStationUsers :id="station?.id"/>
            </div>
        </div>
    </Dialog>
</template>
<script setup lang="ts">
import Dialog from 'primevue/dialog';
import { provide, ref } from 'vue';
import SeeStationMap from './SeeStationMap.vue';
import SeeStationDetails from './SeeStationDetails.vue';
import SeeStationUsers from './SeeStationUsers.vue';
import { StationTypes } from '../../types/stationTypes';

const visible = ref<boolean>(false)
const station = ref<StationTypes>()

function open(props: StationTypes) {
    station.value = props
    visible.value = true
}


defineExpose({
    open
})

provide('station', station)

</script>