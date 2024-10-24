<template>
    <DataTable :value="data?.data" removableSort paginator :totalRecords="data?.total" :rows="data?.per_page"
        class="border rounded-md" @sort="handleSort($event)" @page="handleChangePage($event)" @pagination="onPagination"
        lazy :rowsPerPageOptions="[5, 10, 20, 50]">
        <template #header>
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center space-x-2">
                    <p>Peoples</p>
                    <Select @change="handleChangeRole" v-model="filters.role" :options="roles" optionValue="name"
                        optionLabel="label" class="w-fit " />
                </div>
                <div class="">
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters.searchString" placeholder="Keyword Search" @input="handleSearch" />
                    </IconField>
                </div>
            </div>
        </template>
        <Column field="name" header="Name" sortable />
        <Column field="user_name" header="Username" sortable />
        <Column field="position" header="Position" sortable />
        <template #empty>
            <div class="text-center">
                No Record found.
            </div>
        </template>
    </DataTable>
</template>
<script setup lang="ts">
import DataTable, { DataTablePageEvent, DataTableSortEvent } from 'primevue/datatable';
import Select from 'primevue/select';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import Column from 'primevue/column';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { FilterTypes, PaginationTypes, StationWithUserPaginationTypes } from '../../types/stationTypes';
import { useDebounceFn } from '@vueuse/core';

const props = defineProps(['id'])


onMounted(() => {
    reloadTable()
})

interface FilterWithRoleTypes extends FilterTypes {
    role: string
}
interface RoleTypes {
    name: string,
    guard_name: string,
    label: string
}

const filters = ref<FilterWithRoleTypes>({
    page: 1,
    sortBy: 'id',
    sortType: 'desc',
    rows: 10,
    searchString: '',
    role: 'admin_staff'
})

const data = ref<StationWithUserPaginationTypes>()
const roles = ref<RoleTypes[]>()

onMounted(() => {
    getRoles()
    reloadTable()
})

async function reloadTable() {
    const res = await axios.get(route('stations.users', [props?.id]), { params: filters.value })

    if (res.status === 200) {
        data.value = res.data as StationWithUserPaginationTypes
    }
}

async function getRoles() {
    const res = await axios.get(route('roles.index'))

    if (res.status === 200) {
        roles.value = res.data as RoleTypes[]
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

function handleChangeRole() {
    reloadTable()
}

function onPagination() {
    console.log('trigger')
}

const handleSearch = useDebounceFn(() => {
    reloadTable()
}, 1000)

</script>