<template>
    <IconField iconPosition="left">
        <InputIcon>
            <i class="pi pi-search" />
        </InputIcon>
        <InputText v-model="model" placeholder="Search" @input="handleSearch" />
    </IconField>
</template>
<script setup lang="ts">
import { useDebounceFn } from '@vueuse/core';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';

const model = defineModel<any>();
const emit = defineEmits(['callback'])

const handleSearch = useDebounceFn(() => {
    emit('callback')
}, 1000)
</script>