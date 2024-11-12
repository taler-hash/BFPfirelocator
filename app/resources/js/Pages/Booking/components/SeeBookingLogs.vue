<template>
    <div class="!text-sm">
        <Timeline :value="props.logs">
            <template #content="slotProps">
                <div class="p-2 border rounded-md shadow mb-2 w-[230px]">
                    <div class="text-base font-medium">{{ slotProps.item.event }}</div>
                    <div class="text-nowrap text-sm text-gray-500">{{ slotProps.item.causer.name }}</div>
                    <div v-if="slotProps.item.event === 'created'">
                        <p>Booking created by: {{ slotProps.item.causer.name }}</p>
                    </div>
                    <div v-else-if="slotProps.item.event === 'updated'">
                        <div v-for="(item, key) in slotProps.item.properties.old" class=" text-xs">
                            <p>{{ key }} updated from <span class="font-bold">{{ item }}</span> to <span class="font-bold">{{ slotProps.item.properties.attributes[key] }}</span> </p>
                        </div>
                    </div>
                </div>
            </template>
        </Timeline>
    </div>
</template>
<script setup lang="ts">
import Timeline from 'primevue/timeline';

const props = defineProps(['logs'])

</script>