<template>
    <Dialog v-model:visible="visible" modal header="Hydrant" class="w-96">
        <div class="flex flex-col sm:flex-row w-full">
            <div class="flex-grow">
                <div class="w-full h-48">
                    <SeeMap 
                        v-model:latitude="hydrant.latitude" 
                        v-model:longitude="hydrant.longitude"/>
                </div>
                <SeeHydrantDetails />
            </div>
        </div>
    </Dialog>
</template>
<script setup lang="ts">
import Dialog from 'primevue/dialog';
import { ref } from 'vue';
import SeeMap from '@/Components/SeeMap/SeeMap.vue';
import SeeHydrantDetails from './SeeHydrantDetails.vue';
import { HydrantTypes } from '../../types/hydrantTypes';
import UserList from '@/Pages/User/UserList.vue';
import { provide } from 'vue';

const visible = ref<boolean>(false)
const hydrant = ref<HydrantTypes>({
    longitude: 0,
    latitude: 0,
})

function open(props: HydrantTypes) {
    hydrant.value = props
    visible.value = true
}

provide('hydrant', hydrant)
defineExpose({
    open,
})

</script>