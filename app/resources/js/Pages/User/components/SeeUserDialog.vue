<template>
    <Dialog v-model:visible="visible" modal :header="`${userListProps.header}`" @hide="close">
        <div class="flex items-start w-full">
            <div class="space-y-4">
                <div class="flex flex-col gap-2">
                    <label for="user_name">Username</label>
                    <InputText id="user_name" v-model="user.user_name" aria-describedby="user_name-help" required disabled/>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="name">Name</label>
                    <InputText id="name" v-model="user.name" aria-describedby="name-help" required disabled/>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="station">Station</label>
                    <StationSelect v-model="user.station_id" disabled/>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="position">Position</label>
                    <InputText id="position" v-model="user.position" aria-describedby="position-help" disabled/>
                </div>
            </div>
            
        </div>
    </Dialog>
</template>
<script setup lang="ts">
import Dialog from 'primevue/dialog';
import StationSelect from './StationSelect.vue';
import InputText from 'primevue/inputtext';
import { inject, onMounted, ref } from 'vue';
import SeeUserDialogLogs from './SeeUserDialogLogs.vue';
import { UserFormTypes } from '../types/UserTypes';
import axios from 'axios';

const userListProps = inject<any>('userListProps')
const visible = ref<boolean>(false)
const user = ref<UserFormTypes>({})
const id = ref<number>()

function open(_id: number) {
    id.value = _id
    getUser()
    visible.value = true
}

function close() {
    visible.value = false
}

async function getUser() {
    const res = await axios.get(route('users.show', id.value))

    if (res.status === 200) {
        user.value = res.data as UserFormTypes
    }
}

defineExpose({
    open
})
</script>