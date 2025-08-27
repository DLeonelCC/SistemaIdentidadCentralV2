<template>
    <div>
        <div class="card">
            <div class="flex flex-wrap items-start justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-semibold mb-2">Sistemas</h1>
                    <p class="text-lg text-gray-500">Listado de sistemas, aqui se muestran todos los sistemas.</p>
                </div>
                <div>
                    <Button label="Nuevo" icon="pi pi-plus" @click="dialog = true" />
                </div>
            </div>

            <div class="mb-4">
                <IconField>
                    <InputIcon><i class="pi pi-search" /></InputIcon>
                    <InputText v-model="filters['global'].value" @input="onSearch" placeholder="Buscar..." />
                </IconField>
            </div>

            <DataTable ref="dt" v-model:selection="selectedItems" :value="Items" :lazy="true" dataKey="id"
                :paginator="true" :rows="pagination.rows" :totalRecords="pagination.totalRecords" @page="onPage"
                @sort="onSort" :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 15, 25, 50, 100]"
                currentPageReportTemplate="Mostrando del {first} al {last} de {totalRecords} elementos">

                <template #empty>No se encontraron elementos coincidentes.</template>

                <Column field="tenant_id" header="Tenant" style="width: 7rem">
                    <template #body="slotProps">
                        <Button icon="pi pi-bookmark" severity="secondary" rounded aria-label="Bookmark" size="small">U</Button>
                    </template>
                </Column>
                <Column field="id" sortable header="Id" style="width: 10rem"></Column>
                <Column field="color" header="Color" style="min-width: 7rem">
                    <template #body="slotProps">
                        <div class="w-6 h-6 rounded-md border" :style="{ backgroundColor: '#' + slotProps.data.color }">
                        </div>
                    </template>
                </Column>
                <Column field="codigo" sortable header="Código" style="min-width: 7rem"></Column>
                <Column field="nombre" sortable header="Nombre" style="min-width: 7rem"></Column>
                <Column field="descripcion" header="Descripción" style="min-width: 7rem"></Column>
                <Column field="url" header="Url" style="min-width: 7rem"></Column>
                <Column :exportable="false" header="Acciones" style="width: 7rem">
                    <template #body="slotProps">
                        <div class="flex">
                            <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="edit(slotProps.data)" />
                            <Button icon="pi pi-trash" outlined rounded severity="danger"
                                @click="confirmDelete(slotProps.data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="dialog" :style="{ width: '700px' }" header="Sistema" :modal="true">
            <div class="flex flex-col gap-6">
                <SistemasForm ref="elSistemasForm"
                    @saved="dialog = false; submitted = false; toast.add({ severity: 'success', summary: 'Éxito', detail: 'Operación exitosa', life: 3000 }); getData();"
                    @notsaved="submitted = false">
                </SistemasForm>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="dialog = false" />
                <Button label="Guardar" icon="pi pi-check" @click="submitted = true; $refs.elSistemasForm.save()"
                    :loading="submitted" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deletedialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="item">¿Estás seguro de que quieres eliminar a <b>{{ item.nombre }}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" text @click="deletedialog = false" />
                <Button label="Yes" icon="pi pi-check" @click="deleteItem" />
            </template>
        </Dialog>

        <Toast />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { debounce } from 'lodash';
import SistemasService from '@/services/SistemasService.js';
import SistemasForm from './SistemasForm.vue';

const toast = useToast();

const elSistemasForm = ref(null);
const dt = ref();
const Items = ref([]);
const dialog = ref(false);
const deletedialog = ref(false);
const item = ref({});
const selectedItems = ref([]);
const submitted = ref(false);

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

const pagination = ref({
    page: 0,
    rows: 15,
    totalRecords: 0,
    sortField: null,
    sortOrder: null,
});

onMounted(() => {
    getData();
});

function getData() {
    SistemasService.getData({
        params: {
            page: pagination.value.page + 1,
            page_size: pagination.value.rows,
            sort_by: pagination.value.sortField ?? 'id',
            direction: pagination.value.sortOrder === 1 ? 'asc' : 'desc' ?? 'desc',
            search: filters.value.global.value,
            tipo: 'NORMAL',
        }
    }).then(response => {
        Items.value = response.data;
        pagination.value.totalRecords = response.total;
    });
}

function onPage(event) {
    pagination.value.page = event.page;
    pagination.value.rows = event.rows;
    getData();
}

function onSort(event) {
    pagination.value.sortField = event.sortField;
    pagination.value.sortOrder = event.sortOrder;
    pagination.value.page = 0;
    getData();
}

const debouncedSearch = debounce(() => {
    pagination.value.page = 0;
    getData();
}, 700);

function onSearch() {
    debouncedSearch();
}

function edit(row) {
    dialog.value = true;
    setTimeout(() => {
        elSistemasForm.value.setValue(row);
    }, 50);
}

function confirmDelete(row) {
    item.value = row;
    deletedialog.value = true;
}

async function deleteItem() {
    try {
        deletedialog.value = false;
        await SistemasService.delete(item.value.id);
        const nombre = item.value.nombre;
        toast.add({ severity: 'success', summary: 'Eliminado', detail: `Sistema ${nombre} eliminado`, life: 3000 });
        item.value = {};
        getData();
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al eliminar el Sistema', life: 3000 });
    }
}

function exportCSV() {
    dt.value.exportCSV();
}
</script>
