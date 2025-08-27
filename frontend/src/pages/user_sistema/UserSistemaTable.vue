<template>
    <div>
        <div class="card">
            <div class="flex flex-wrap items-start justify-between mb-4">
                <div>
                    <p class="text-xl font-semibold">Access To Systems</p>
                </div>
                <div>
                    <!----->
                </div>
            </div>

            <Message severity="secondary" :closable="false" class="mb-4">
                En esta sección se listan todos los sistemas a los que el usuario tiene acceso.
            </Message>

            <div class="flex flex-wrap items-start mb-4">
                <div class="col-span-12 mr-4">
                    <SelectSistema ref="elSelectSistema" @selectedItem="onSistemaSelected"></SelectSistema>
                </div>

                <Button label="Asignar" icon="pi pi-plus" @click="add()" :loading="submitted" />
            </div>

            <DataTable ref="dt" v-model:selection="selectedItems" :value="Items" :lazy="true" dataKey="id"
                :paginator="true" :rows="pagination.rows" :totalRecords="pagination.totalRecords" @page="onPage"
                @sort="onSort" :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 15, 25, 50, 100]"
                currentPageReportTemplate="Mostrando del {first} al {last} de {totalRecords} elementos">

                <template #empty>No se encontraron elementos coincidentes.</template>

                <Column field="id" sortable header="Id" style="width: 10rem"></Column>
                <Column field="color" header="Color" style="min-width: 7rem">
                    <template #body="slotProps">
                        <div class="w-6 h-6 rounded-md border" :style="{ backgroundColor: '#' + slotProps.data.color }">
                        </div>
                    </template>
                </Column>
                <Column field="nombre" sortable header="Nombre" style="min-width: 7rem"></Column>
                <Column field="roles" header="Roles" style="min-width: 7rem">
                    <template #body="slotProps">
                        <span v-for="(role, index) in slotProps.data.roles" :key="index">
                            {{ role.name }}<span v-if="index < slotProps.data.roles.length - 1">, </span>
                        </span>
                    </template>
                </Column>
                <Column field="active" header="Activo" style="min-width: 7rem">
                    <template #body="slotProps">
                        <Checkbox v-model="slotProps.data.active" :binary="true" :true-value="1" :false-value="0"
                            :inputId="'activo_' + slotProps.data.id" @change="onActiveChange(slotProps.data)" />
                    </template>
                </Column>
                <Column :exportable="false" header="Acciones" style="width: 7rem">
                    <template #body="slotProps">
                        <div class="flex">
                            <Button icon="pi pi-sitemap" outlined rounded class="mr-2"
                                @click="editRol(slotProps.data)" />
                            <Button icon="pi pi-trash" outlined rounded severity="danger"
                                @click="confirmDelete(slotProps.data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="roledialog" :style="{ width: '700px' }" header="Roles" :modal="true">
            <div class="flex flex-col gap-6">
                <RolesTable ref="elRolesTable" :user_id="user_id" :sistema_id="selectedItem.id" @saved="getData();">
                </RolesTable>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="roledialog = false" />
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
    import UsersService from '@/services/UsersService.js';
    import SelectSistema from '@/components/SelectSistema.vue';
    import RolesTable from '@/pages/roles/RolesTable.vue';
    import SistemasService from '@/services/SistemasService.js';

    const toast = useToast();

    const props = defineProps({
        user_id: { type: Number, default: null, required: true },
    });

    const dt = ref();
    const Items = ref([]);
    const roledialog = ref(false);
    const deletedialog = ref(false);
    const item = ref({});
    const selectedItems = ref([]);

    const elSelectSistema = ref(null);
    const sistema = ref({});
    const submitted = ref(false);

    const elRolesTable = ref(null);
    const selectedItem = ref(null);

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
        UsersService.getSistemas({
            params: {
                page: pagination.value.page + 1,
                page_size: pagination.value.rows,
                sort_by: pagination.value.sortField ?? 'id',
                direction: pagination.value.sortOrder === 1 ? 'asc' : 'desc' ?? 'desc',
                search: filters.value.global.value,

                user_id: props.user_id,
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

    async function add() {
        submitted.value = true;
        try {
            await UsersService.addSistema({ user_id: props.user_id, sistema_id: sistema.value.id });
            submitted.value = false;
            elSelectSistema.value.reset();
            toast.add({ severity: 'success', summary: 'Éxito', detail: `Operación Exitosa`, life: 3000 });
            sistema.value = {};
            getData();
        } catch (e) {
            console.log(e);
            submitted.value = false;
        }
    };

    function confirmDelete(row) {
        item.value = row;
        deletedialog.value = true;
    }

    async function deleteItem() {
        try {
            deletedialog.value = false;
            await UsersService.deleteSistema(item.value.id_user_sistema);
            const nombre = item.value.nombre;
            toast.add({ severity: 'success', summary: 'Eliminado', detail: `Sistema ${nombre} eliminado`, life: 3000 });
            item.value = {};
            getData();
        } catch (error) {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Error al eliminar el Sistema', life: 3000 });
        }
    }

    function editRol(row) {
        selectedItem.value = row;
        roledialog.value = true;
    }

    function onSistemaSelected(event) {
        sistema.value = event;
    }

    async function onActiveChange(row) {
        await SistemasService.changeAccessUserSistema({
            id_user_sistema: row.id_user_sistema,
            active: row.active,
        });
        getData();
    }

    function exportCSV() {
        dt.value.exportCSV();
    }
</script>
