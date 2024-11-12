<template>
    <div class="">
        <BookingList
            :stationId="page.props.auth.user.station.id"
            :canEdit="true"
            :canAssign="true"
            role="admin_staff"/>
    </div>
</template>
<script setup lang="ts">
import BookingList from './BookingList.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage<any>()
</script>