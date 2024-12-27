<template>
    <Dialog v-model:visible="visible" modal header="Station" class="w-full sm:w-[calc(100%-4rem)]">
        <div class="flex flex-col sm:flex-row">
            <div>
                <div class="sm:w-48 sm:h-48 w-full h-48">
                    <SeeMap 
                        v-model:latitude="station.latitude" 
                        v-model:longitude="station.longitude"/>
                </div>
                <SeeStationDetails />
            </div>
            <div class="w-full pt-4 sm:pt-0">
                <UserList :stationId="station?.id" role="admin_staff" :filterRole="true" />
            </div>
        </div>
    </Dialog>
</template>
<script setup lang="ts">
import Dialog from 'primevue/dialog';
import { provide, ref } from 'vue';
import SeeMap from '@/Components/SeeMap/SeeMap.vue';
import SeeStationDetails from './SeeStationDetails.vue';
import { StationTypes } from '../../types/stationTypes';
import UserList from '@/Pages/User/UserList.vue';

const visible = ref<boolean>(false)
const station = ref<StationTypes>({
    longitude: 0,
    latitude: 0,
})

function open(props: StationTypes) {
    station.value = props
    visible.value = true
}

provide('station', station)

defineExpose({
    open
})

</script>