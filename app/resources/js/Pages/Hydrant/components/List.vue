<template>
    <DataTable :value="data?.data" removableSort paginator :totalRecords="data?.total" :rows="data?.per_page"
        @sort="handleSort($event)" @page="handleChangePage($event)" lazy :rowsPerPageOptions="[5, 10, 20, 50]">
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
                    <Button icon="pi pi-plus" severity="success" @click="$refs.sth?.open()" />
                </div>
            </div>
        </template>
        <Column field="name" header="Name" style="width: 25%" sortable class="text-nowrap" />
        <Column style="width: 10%">
            <template #header>
                <p class="text-center w-full">Actions</p>
            </template>
            <template #body="props">
                <div class="flex flex-nowrap justify-center">
                    <Button severity="info" icon="pi pi-eye" text @click="$refs.seh?.open(props.data)" />
                    <Button severity="warn" icon="pi pi-pencil" text @click="$refs.edh?.open(props.data)" />
                    <Button severity="danger" icon="pi pi-times" text @click="handleDeleteHydrant(props.data.id)" />
                </div>
            </template>
        </Column>
        <template #empty>
            <div class="text-center">
                No Record found.
            </div>
        </template>
    </DataTable>
    <StoreHydrantDialog ref="sth" />
    <SeeHydrantDialog ref="seh" />
    <EditHydrantDialog ref="edh" />
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
import { FilterTypes, HydrantPaginationTypes } from '../types/hydrantTypes';
import { DataTableSortEvent, DataTablePageEvent } from 'primevue/datatable';
import StoreHydrantDialog from './StoreHydrant/StoreHydrantDialog.vue';
import SeeHydrantDialog from './SeeHydrant/SeeHydrantDialog.vue';
import EditHydrantDialog from './EditHydrant/EditHydrantDialog.vue';
import { useConfirm } from "primevue/useconfirm";
import { useDebounceFn } from '@vueuse/core'
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

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
const data = ref<HydrantPaginationTypes>()
const confirm = useConfirm()
const form = useForm<any>({})
const toast = useToast()

onMounted(() => {
    reloadTable()
})

async function reloadTable() {
    const res = await axios.get(route('hydrants.index'), { params: filters.value })

    if (res.status === 200) {
        data.value = res.data as HydrantPaginationTypes
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

const handleSearch = useDebounceFn(() => {
    reloadTable()
}, 1000)

function handleDeleteHydrant(id: number) {
    confirm.require({
        message: 'Are you sur you want to delete this Hydrant',
        header: 'Confirmation',
        icon: 'pi pi-info-circle',
        accept: () => {
            submitDeleteHydrant(id)
        }
    })

}

function submitDeleteHydrant(id: number) {
    form.delete(route('hydrants.delete', id), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Deleted Hydrant Successfully', life: 3000 });
            reloadTable()
        }
    })
}

provide('reloadTable', reloadTable)

</script>