<template>
    <DataTable :value="data?.data" removableSort paginator :totalRecords="data?.total" :rows="data?.per_page"
        @sort="handleSort($event)" @page="handleChangePage($event)" @pagination="onPagination" lazy
        :rowsPerPageOptions="[5, 10, 20, 50]">
        <template #header>
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center space-x-2">
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters.searchString" placeholder="Keyword Search" @input="handleSearch" />
                    </IconField>
                </div>
                <div class="flex items-center-space-x-2">
                    <Button icon="pi pi-plus" severity="success" @click="$refs.sts?.open()"/>
                </div>
            </div>
        </template>
        <Column field="name" header="Name" style="width: 25%" sortable class="text-nowrap" />
        <Column field="location" header="Location" sortable class="text-nowrap text-ellipsis" style="width: 25%;">
            <template #body="props">
                <div class="w-96 text-ellipsis overflow-hidden" v-tooltip.bottom="props.data.location">
                    {{ props.data.location }}
                </div>
            </template>
        </Column>
        <Column style="width: 10%">
            <template #header>
                <p class="text-center w-full">Actions</p>
            </template>
            <template #body="props">
                <div class="flex flex-nowrap justify-center" v-if="props.data.show_action">
                    <Button severity="info" icon="pi pi-eye" text @click="$refs.ses?.open(props.data)"/>
                    <Button severity="warn" icon="pi pi-pencil" text />
                    <Button severity="danger" icon="pi pi-times" text />
                </div>
            </template>
        </Column>
        <template #empty>
            <div class="text-center">
                No Record found.
            </div>
        </template>
    </DataTable>
    <StoreStationDialog ref="sts"/>
    <SeeStationDialog ref="ses" />
</template>
<script lang="ts" setup>
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import axios from 'axios';
import { onMounted, defineProps, ref, provide } from 'vue';
import { PaginationTypes, FilterTypes, StationPaginationTypes } from '../types/stationTypes';
import { DataTableSortEvent, DataTablePageEvent } from 'primevue/datatable';
import StoreStationDialog from './StoreStation/StoreStationDialog.vue';
import SeeStationDialog from './SeeStation/SeeStationDialog.vue';
import { useDebounceFn } from '@vueuse/core'

const props: { id?: number } = defineProps({
    id: Number
})

const filters = ref<FilterTypes>({
    id: props?.id,
    page: 1,
    sortBy: 'id',
    sortType: 'desc',
    rows: 10,
    searchString: ''
})
const data = ref<StationPaginationTypes>()

onMounted(() => {
    reloadTable()
})

async function reloadTable() {
    const res = await axios.get(route('stations.index'), { params: filters.value })

    if (res.status === 200) {
        data.value = res.data as StationPaginationTypes
    }
}

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

function onPagination() {
    console.log('trigger')
}

const handleSearch = useDebounceFn(() => {
    reloadTable()
}, 1000)

provide('reloadTable', reloadTable)

</script>