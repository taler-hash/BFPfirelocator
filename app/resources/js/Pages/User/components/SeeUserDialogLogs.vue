<template>
    <div class="">{{  id  }}</div>
</template>
<script setup lang="ts">
import axios from 'axios';
import { onMounted } from 'vue';

const id = defineProps(['id'])

onMounted(() => {
    getLogs()
})


async function getLogs() {
    const res = await axios.get(route('activitylogs.show', id))

    if(res.status === 200) {
    } 
}


</script>