<template>
    <div class="flex items-center">
        <p class="font-medium">No Booking Available.</p>
        <Button size="small" severity="info" icon="pi pi-refresh" text @click="refreshPage"/>
    </div>
</template>
<script setup lang="ts">
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';

function refreshPage() {
    window.location.reload()
}
</script>