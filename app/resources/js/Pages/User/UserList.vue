<template>
    <DataTable :value="data?.data" removableSort paginator :totalRecords="data?.total" :rows="data?.per_page"
        class="border rounded-md" @sort="handleSort($event)" @page="handleChangePage($event)"
        lazy :rowsPerPageOptions="[5, 10, 20, 50]">
        <template #header>
            <div class="flex flex-wrap items-center justify-between">
                <div v-if="props.filterRole" class="flex items-center space-x-2">
                    <Select @change="handleChangeRole" v-model="filters.role" :options="roles" optionValue="name"
                        optionLabel="label" class="w-fit " />
                </div>
                <div  class="flex items-center space-x-2">
                    <SearchInput @callback="reloadTable()"/>
                    <StationFilter v-if="props.filterStation" v-model="filters.stationId" @change="handleFilterStation"/>
                </div>
                <div class="flex items-center-space-x-2" v-if="props.crud">
                    <Button icon="pi pi-plus" severity="success" @click="$refs.sud?.open()" />
                </div>
            </div>
        </template>
        <Column field="name" header="Name" sortable />
        <Column field="user_name" header="Username" sortable />
        <Column field="station.name" header="Station" />
        <Column field="position" header="Position" sortable />
        <Column v-if="props.crud" style="width: 10%">
            <template #header>
                <p class="text-center w-full">Actions</p>
            </template>
            <template #body="props">
                <div class="flex flex-nowrap justify-center">
                    <Button severity="info" icon="pi pi-eye" text @click="$refs.seud?.open(props.data.id)"/>
                    <Button severity="warn" icon="pi pi-pencil" text @click="$refs.eud?.open(props.data.id)" />
                    <Button severity="danger" icon="pi pi-times" text @click="handleDeleteUser(props.data.id)"/>
                </div>
            </template>
        </Column>
        <template #empty>
            <div class="text-center">
                No Record found.
            </div>
        </template>
    </DataTable>
    <StoreUserDialog ref="sud" />
    <EditUserDialog ref="eud" />
    <SeeUserDialog ref="seud"/>
</template>
<script setup lang="ts">
import DataTable, { DataTablePageEvent, DataTableSortEvent } from 'primevue/datatable';
import Select from 'primevue/select';
import SearchInput from '@/Components/SearchInput/SearchInput.vue';
import Column from 'primevue/column';
import Button from 'primevue/button';
import StationFilter from '../../Components/StationFilter/StationFilter.vue';
import StoreUserDialog from './components/StoreUserDialog.vue';
import SeeUserDialog from './components/SeeUserDialog.vue';
import EditUserDialog from './components/EditUserDialog.vue';
import { onMounted, provide, ref } from 'vue';
import axios from 'axios';
import { FilterTypes, UserPaginationTypes, UserTypes } from './types/UserTypes';
import { useDebounceFn } from '@vueuse/core';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { useForm } from '@inertiajs/vue3';

const props = defineProps(['stationId', 'role', 'header', 'crud', 'filterStation', 'filterRole'])
const confirm = useConfirm()
const toast = useToast()

const form = useForm<UserTypes>({
    id: null,
})

interface FilterWithRoleTypes extends FilterTypes {
    role: string,
    stationId: number
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
    role: props?.role,
    stationId: props?.stationId ?? 0
})

const data = ref<UserPaginationTypes>()
const roles = ref<RoleTypes[]>()

onMounted(async () => {
    getRoles()
    await reloadTable()
})

async function reloadTable() {
    const res = await axios.get(route('users.index'), { params: filters.value })

    if (res.status === 200) {
        data.value = res.data as UserPaginationTypes
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

const handleSearch = useDebounceFn(() => {
    reloadTable()
}, 1000)

function handleFilterStation () {
    reloadTable()
}

function handleDeleteUser(id: number) {
    confirm.require({
        message: `Are you sure you want to delete this ${props.header}`,
        header: 'Confirmation',
        icon: 'pi pi-info-circle',
        accept: () => {
            submitDeleteUser(id)
        }
    })

}

function submitDeleteUser(id: number) {
    form.delete(route('users.delete', id), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: `Deleted ${props.header} Successfully`, life: 3000 });
            reloadTable()
        }
    })
}

provide('userListProps', props)
provide('reloadTable', reloadTable)

</script>