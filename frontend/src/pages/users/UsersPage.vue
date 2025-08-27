<template>
    <div>
        <div class="card">
            <div class="flex flex-wrap items-start justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-semibold mb-2">Usuarios</h1>
                    <p class="text-lg text-gray-500">Listado de usuarios, aqui se muestran todos los usuarios.</p>
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
                        <Button icon="pi pi-bookmark" severity="secondary" rounded aria-label="Bookmark" size="small">{{slotProps.data.tenant_id}}</Button>
                    </template>
                </Column>
                <Column field="id" sortable header="Id" style="width: 10rem"></Column>
                <Column field="foto" header="Foto" style="min-width: 7rem">
                    <template #body="slotProps">
                        <div class="flex items-center">
                            <Avatar shape="circle"
                                style="width: 35px; height: 35px; background-color: orange; color: white;">
                                <template v-if="slotProps.data.photo">
                                    <Image :src="storage + slotProps.data.photo"
                                        imageStyle="object-fit: cover; width: 100%; height: 100%; border-radius: 50%;" />
                                </template>
                                <template v-else>
                                    <span class="text-lg font-semibold">
                                        {{ slotProps.data.persona?.nombres?.substring(0, 1) ??
                                            slotProps.data.name?.substring(0, 1) }}
                                    </span>
                                </template>
                            </Avatar>
                        </div>
                    </template>
                </Column>
                <Column field="tipo" sortable header="Tipo" style="min-width: 7rem"></Column>
                <Column field="name" sortable header="Usuario" style="min-width: 7rem">
                    <template #body="slotProps">
                        <span>{{ slotProps.data.name }}</span>
                        <br>
                        <span class="text-gray-500">{{ slotProps.data.email }}</span>
                    </template>
                </Column>
                <Column field="persona" header="Persona" style="min-width: 7rem">
                    <template #body="slotProps">
                        <span v-if="slotProps.data.persona">{{ slotProps.data.persona.tipo_doc }} - {{
                            slotProps.data.persona.num_doc }}</span>
                        <br>
                        <span v-if="slotProps.data.persona" class="text-gray-500">{{
                            slotProps.data.persona.nombre_completo }}</span>
                    </template>
                </Column>
                <Column field="roles" header="Roles" style="min-width: 7rem">
                    <template #body="slotProps">
                        <span v-for="(role, index) in slotProps.data.roles" :key="index">
                            {{ role.name }}<span v-if="index < slotProps.data.roles.length - 1">, </span>
                        </span>
                    </template>
                </Column>
                <Column field="estado" header="Active" style="width: 10rem">
                    <template #body="slotProps">
                        <Tag :value="slotProps.data.estado"
                            :severity="getStatusLabel(slotProps.data.estado)" />
                    </template>
                </Column>
                <Column :exportable="false" header="Acciones" style="width: 7rem">
                    <template #body="slotProps">
                        <div class="flex">
                            <Button icon="pi pi-arrow-right" outlined rounded class="mr-2"
                                @click="$router.push(`/usuarios/${slotProps.data.id}`)" />
                            <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="edit(slotProps.data)" />
                            <Button icon="pi pi-trash" outlined rounded severity="danger"
                                @click="confirmDelete(slotProps.data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="dialog" :style="{ width: '700px' }" header="Usuario" :modal="true">
            <div class="flex flex-col gap-6">
                <UsersForm ref="elUsersForm"
                    @saved="dialog = false; submitted = false; toast.add({ severity: 'success', summary: 'Éxito', detail: 'Operación exitosa', life: 3000 }); getData();"
                    @notsaved="submitted = false">
                </UsersForm>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="dialog = false" />
                <Button label="Guardar" icon="pi pi-check" @click="submitted = true; $refs.elUsersForm.save()"
                    :loading="submitted" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deletedialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="item">¿Estás seguro de que quieres eliminar a <b>{{ item.username }}</b>?</span>
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
import UsersService from '@/services/UsersService.js';
import UsersForm from './UsersForm.vue';

const storage = import.meta.env.VITE_APP_API_URL_STORAGE;

const toast = useToast();

const elUsersForm = ref(null);
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
    let ordering = null;
    if (pagination.value.sortField) {
        ordering = pagination.value.sortOrder === 1
            ? pagination.value.sortField
            : '-' + pagination.value.sortField;
    }

    UsersService.getData({
        params: {
            page: pagination.value.page + 1, // Recuerda que en PrimeVue page comienza en 0
            page_size: pagination.value.rows,
            search: filters.value.global.value,
            ordering: ordering
        }
    }).then(response => {
        Items.value = response.data; // 👈 Aquí toma solo el arreglo de usuarios
        pagination.value.totalRecords = response.total; // 👈 Aquí toma el total real
    });
}

function onPage(event) {
    pagination.value.page = event.page;
    pagination.value.rows = event.rows;
    getData();
}

function onSort(event) {
    console.log('Sorting', event);
    pagination.value.sortField = event.sortField;
    pagination.value.sortOrder = event.sortOrder;
    pagination.value.page = 0; // (opcional) volver a la primera página cuando haces sort
    getData();
}

const debouncedSearch = debounce(() => {
    pagination.value.page = 0; // Siempre volver a la primera página
    getData();
}, 700);

function onSearch() {
    debouncedSearch();
}

function edit(row) {
    dialog.value = true;
    setTimeout(() => {
        elUsersForm.value.setValue(row);
    }, 50);
}

function confirmDelete(row) {
    item.value = row;
    deletedialog.value = true;
}

async function deleteItem() {
    try {
        deletedialog.value = false;
        await UsersService.delete(item.value.id);
        const nombre = item.value.username;
        toast.add({ severity: 'success', summary: 'Eliminado', detail: `Usuario ${nombre} eliminado`, life: 3000 });
        item.value = {};
        getData();
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error al eliminar el usuario', life: 3000 });
    }
}

function getStatusLabel(status) {
    return status === 'ACTIVO' ? 'success' : 'danger';
}

function exportCSV() {
    dt.value.exportCSV();
}
</script>
