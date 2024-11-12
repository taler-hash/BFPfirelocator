<template>
    <Dialog v-model:visible="visible" modal :header="`Edit ${userListProps.header}`" @hide="close">
        <form @submit.prevent="submit" class="space-y-4">
            <div class="flex flex-col gap-2">
                <label for="user_name">Username</label>
                <InputText id="user_name" v-model="form.user_name" aria-describedby="user_name-help" required />
                <InputError :message="form.errors.user_name" />
            </div>
            <div class="flex flex-col gap-2">
                <label for="name">Name</label>
                <InputText id="name" v-model="form.name" aria-describedby="name-help" required />
                <InputError :message="form.errors.name" />
            </div>
            <div class="flex flex-col gap-2">
                <label for="station">Station</label>
                <StationSelect v-model="form.station_id"/>
                <InputError :message="form.errors.station_id" />
            </div>
            <div class="flex flex-col gap-2">
                <label for="position">Position</label>
                <InputText id="position" v-model="form.position" aria-describedby="position-help" />
                <InputError :message="form.errors.position" />
            </div>
            <div class="flex flex-col gap-2">
                <label for="password">Password</label>
                <InputText id="password" v-model="form.password" aria-describedby="password-help" type="text" />
                <InputError :message="form.errors.password" />
            </div>
            <div class="pt-2">
                <Button type="submit" severity="success" label="submit" class="w-full" />
            </div>
        </form>
    </Dialog>
</template>
<script setup lang="ts">
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import InputError from '@/Components/InputError.vue';
import Button from 'primevue/button';
import StationSelect from './StationSelect.vue';
import { inject, ref } from 'vue';
import { UserFormTypes, UserTypes } from '../types/UserTypes';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';

const reloadTable = inject<any>('reloadTable')
const userListProps = inject<any>('userListProps')
const toast = useToast()
const visible = ref<boolean>(false)
const form = useForm<UserFormTypes>({
    id: 0,
    user_name: '',
    name: '',
    position: null,
    role: userListProps.role,
    station_id: userListProps?.stationId ?? 0 
})

function open(id: number) {
    getUser(id)
    visible.value = true
}

function close() {
    visible.value = false
}

async function getUser(id: number) {
    const res = await axios.get(route('users.show', id))

    if(res.status === 200) {
        form.defaults(res.data)
        form.reset()
    }
}

async function submit() {
    
    form.put(route('users.edit', form.id as number), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: `Update ${userListProps.header} Successfully`, life: 3000 });
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