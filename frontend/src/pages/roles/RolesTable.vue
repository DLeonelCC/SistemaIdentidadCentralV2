<template>
    <div>
        <div class="card">
            <div class="flex flex-wrap items-start mb-4">
                <div class="col-span-12 mr-4">
                    <SelectRol ref="elSelectRol" :sistemaId="props.sistema_id" @selectedItem="onRolSelected">
                    </SelectRol>
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
                <Column field="name" sortable header="Nombre" style="min-width: 7rem"></Column>
                <Column field="permisos" header="Permisos" style="min-width: 20rem">
                    <template #body="slotProps">
                        <span v-for="(permiso, index) in slotProps.data.permisos" :key="index">
                            {{ permiso.label }}<span v-if="index < slotProps.data.permisos.length - 1">, </span>
                        </span>
                    </template>
                </Column>
                <Column :exportable="false" header="Acciones" style="width: 7rem">
                    <template #body="slotProps">
                        <div class="flex">
                            <Button icon="pi pi-trash" outlined rounded severity="danger"
                                @click="confirmDelete(slotProps.data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="deletedialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="item">¿Estás seguro de que quieres eliminar a <b>{{ item.name }}</b>?</span>
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
    import SelectRol from '@/components/SelectRol.vue';

    //Emits
    const emits = defineEmits(["saved", "notsaved"]);

    const toast = useToast();

    const props = defineProps({
        user_id: { type: Number, default: null, required: true },
        sistema_id: { type: Number, default: null, required: true },
    });

    const dt = ref();
    const Items = ref([]);
    const deletedialog = ref(false);
    const item = ref({});
    const selectedItems = ref([]);

    const elSelectRol = ref(null);
    const rol = ref({});
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
        UsersService.getRoles({
            params: {
                page: pagination.value.page + 1,
                page_size: pagination.value.rows,
                sort_by: pagination.value.sortField ?? 'id',
                direction: pagination.value.sortOrder === 1 ? 'asc' : 'desc' ?? 'desc',
                search: filters.value.global.value,

                user_id: props.user_id,
                sistema_id: props.sistema_id,
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
            await UsersService.addRole({ user_id: props.user_id, role_id: rol.value.id });
            submitted.value = false;
            elSelectRol.value.reset();
            toast.add({ severity: 'success', summary: 'Éxito', detail: `Operación Exitosa`, life: 3000 });
            rol.value = {};
            getData();
            emits('saved'); //Para actualizar la tabla
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
            await UsersService.deleteRole({ user_id: props.user_id, role_id: item.value.id });
            const nombre = item.value.name;
            toast.add({ severity: 'success', summary: 'Eliminado', detail: `Rol ${nombre} eliminado`, life: 3000 });
            item.value = {};
            getData();
        } catch (error) {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Error al eliminar el Rol', life: 3000 });
        }
    }

    function onRolSelected(event) {
        rol.value = event;
    }

    function exportCSV() {
        dt.value.exportCSV();
    }
</script>