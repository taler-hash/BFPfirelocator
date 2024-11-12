<template>
    <div class="">
        <BookingList
            :ownerId="page.props.auth.user.id" 
            :canCreate="true"
            :canEdit="true"
            role="brgy_staff" />
    </div>
</template>
<script setup lang="ts">
import BookingList from './BookingList.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage<any>()
</script>