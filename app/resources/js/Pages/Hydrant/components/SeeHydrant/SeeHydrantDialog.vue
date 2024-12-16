<template>
    <Dialog v-model:visible="visible" modal header="Hydrant" class="w-full sm:w-[calc(100%-4rem)]">
        <div class="flex flex-col sm:flex-row">
            <div>
                <div class="sm:w-48 sm:h-48 w-full h-48">
                    <SeeMap 
                        v-model:latitude="hydrant.latitude" 
                        v-model:longitude="hydrant.longitude"/>
                </div>
                <SeeHydrantDetails />
            </div>
            <div class="w-full pt-4 sm:pt-0">
                <UserList :hydrantId="hydrant?.id" role="admin_staff" :filterRole="true" />
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

const visible = ref<boolean>(false)
const hydrant = ref<HydrantTypes>({
    longitude: 0,
    latitude: 0,
    
})

function open(props: HydrantTypes) {
    hydrant.value = props
    visible.value = true
}

defineExpose({
    open
})

</script>