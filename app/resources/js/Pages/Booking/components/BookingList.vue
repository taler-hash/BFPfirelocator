<template>
    <DataTable :value="data?.data" removableSort paginator :totalRecords="data?.total" :rows="data?.per_page"
        class="border rounded-md" @sort="handleSort($event)" @page="handleChangePage($event)" lazy
        :rowsPerPageOptions="[5, 10, 20, 50]">
        <template #header>
            <div class="sm:flex items-center justify-between space-y-2 sm:space-y-0">
                <div class="sm:flex items-center space-x-2 space-y-2 sm:space-y-0">
                    <SearchInput v-model="filters.searchString" @callback="reloadTable()" />
                    <StationFilter v-if="props.filterStation" v-model="filters.stationId" @change="reloadTable()"/>
                </div>
                <div class="flex items-center space-x-2">
                    <Button icon="pi pi-refresh" severity="info" @click="reloadTable()"/>
                    <Button v-if="props.canCreate" icon="pi pi-plus" severity="success" @click="$refs.stbd?.open()" />
                </div>
            </div>
        </template>
        <Column field="location_name" header="Location" sortable/>
        <Column field="station.name" header="Station"/>
        <Column field="status" header="Status" sortable>
            <template #body="props">
                <div class="">
                    <Badge :class="statusSeverity(props.data.status)" :value="props.data.status" size="large" />
                </div>
            </template>
        </Column>
        <Column style="width: 10%">
            <template #header>
                <p class="text-center w-full">Actions</p>
            </template>
            <template #body="_props">
                <div class="flex flex-nowrap justify-between">
                    <Button
                        v-tooltip.bottom="'View Booking'" 
                        severity="info"
                        icon="pi pi-eye"
                        text
                        @click="$refs.sbd?.open(_props.data)"
                        />
                    <Button 
                        :class="[_props.data.status === 'ongoing' ? 'visible' : 'invisible']"
                        v-tooltip.bottom="'View Booking Map'" 
                        severity="info" 
                        icon="pi pi-map" 
                        text 
                        @click="$refs.sbmd?.open(_props.data)"/>
                    <Button 
                        v-tooltip.bottom="'Assign a firefighter'" 
                        :class="[canAssign(_props.data) ? 'visible' : 'invisible']" 
                        severity="warn" 
                        icon="pi pi-user" 
                        text 
                        @click="$refs.sbrd?.open(_props.data)"/>
                    <Button 
                        v-tooltip.bottom="'Edit Booking'" 
                        :class="[canEdit(_props.data) ? 'visible' : 'invisible']"
                        severity="success" 
                        icon="pi pi-pencil"
                        text 
                        @click="$refs.ebd?.open(_props.data)"/>
                    <Button 
                        :class="[canDelete(_props.data) ? 'visible' : 'invisible']"
                        icon="pi pi-times" 
                        text 
                        severity="danger" 
                        v-tooltip.bottom="'Delete Booking'"
                        @click="handleDelete(_props.data)" />
                </div>
            </template>
        </Column>
        <template #empty>
            <div class="text-center">
                No Record Found
            </div>
        </template>
    </DataTable>
    <StoreBookingDialog ref="stbd"/>
    <StoreBookingResponderDialog ref="sbrd"/>
    <EditBookingDialog ref="ebd"/>
    <SeeBookingMapDialog ref="sbmd"/>
    <SeeBookingDialog ref="sbd" />
    
</template>

<script setup lang="ts">
import DataTable, { DataTablePageEvent, DataTableSortEvent } from 'primevue/datatable';
import SearchInput from '@/Components/SearchInput/SearchInput.vue';
import SeeBookingMapDialog from './SeeBookingMapDialog.vue';
import StoreBookingDialog from './StoreBookingDialog.vue';
import StoreBookingResponderDialog from './StoreBookingResponderDialog/StoreBookingResponderDialog.vue';
import EditBookingDialog from './EditBookingDialog.vue';
import SeeBookingDialog from './SeeBookingDialog.vue'
import Button from 'primevue/button';
import StationFilter from '@/Components/StationFilter/StationFilter.vue';
import Badge from 'primevue/badge';
import Column from 'primevue/column';
import axios from 'axios';
import { BookingFilterTypes, BookingPaginationTypes, BookingTypes, } from '../types/BookingTypes';
import { onMounted, provide, ref } from 'vue';
import { useConfirm } from 'primevue/useconfirm';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const toast = useToast()
const confirm = useConfirm()
const props = defineProps(['stationId', 'ownerId', 'filterStation', 'canCreate', 'canEdit', 'filters', 'canAssign', 'role'])
const data = ref<BookingPaginationTypes>()

provide('bookingProps', props)
provide('reloadTable', reloadTable)
provide('statusSeverity', statusSeverity)

const filters = ref<BookingFilterTypes>({
    page: 1,
    sortBy: 'id',
    sortType: 'desc',
    rows: 10,
    searchString: '',
    stationId: props?.stationId ?? 0,
    ownerId: props?.ownerId ?? 0,
    role: props.role
})

onMounted(() => {
    filters.value = {...filters.value, ...props.filters}
    reloadTable()
})

function handleSort(e: DataTableSortEvent) {
    filters.value.sortBy = e.sortField as string ?? 'id'
    filters.value.sortType = e.sortOrder as number > 0 ? 'asc' : 'desc'

    reloadTable()
}

function handleChangePage(e: DataTablePageEvent) {
    filters.value.rows = e.rows
    filters.value.page = e.page + 1

    reloadTable()
}

async function reloadTable() {
    const res = await axios.get(route('bookings.index'), { params: filters.value })

    if (res.status === 200) {
        data.value = res.data as BookingPaginationTypes
    }
}

function canEdit(_props: BookingTypes): Boolean {
    if((['completed', 'ongoing']).includes(_props.status as string)) { return false }

    if (props.role === 'admin_staff') {
        return true
    }

    if(_props.status === 'pending' && props.role === 'brgy_staff') {
        return true
    }

    return false
}

function canDelete(_props: BookingTypes): Boolean {
        if((['completed', 'ongoing']).includes(_props.status as string)) { return false }

        if(_props.status === 'pending' && props.role === 'brgy_staff') {
            return true
        } 

        if(_props.status === 'accepted' && props.role === 'admin_staff') {
            return true
        }

        return false
}

function canAssign(_props: BookingTypes): Boolean {
    return props.canAssign && !(['completed', 'ongoing', 'pending']).includes(_props.status as string)
}

function statusSeverity(status: string) {
    const severity: any = {
        completed: '!bg-green-600',
        pending: '!bg-yellow-600',
        accepted: '!bg-blue-600',
        ongoing: '!bg-purple-600',
        cancelled: '!bg-red-600'
    }

    return severity[status]
}

function handleDelete(_props: BookingTypes) {
    confirm.require({
        message: 'Are you sure you want to delete this Booking',
        header: 'Confirmation',
        icon: 'pi pi-info-circle',
        accept: () => {
            submitDelete(_props)
        }
    })
}

function submitDelete(_props: BookingTypes) {
    const deleteForm = useForm<BookingTypes>(_props)


    deleteForm.delete(route('bookings.delete', _props.id), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Deleted Booking Successfully', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Record is outdated. Record will refreshed', life: 3000 });

        },
        onFinish: () => {
            reloadTable()
        }
    })
}


</script>