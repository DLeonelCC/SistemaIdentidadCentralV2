<template>
    <Breadcrumb :home="home" :model="items" style="background-color: transparent;" >
        <template #item="{ item, props }">
            <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                <a :href="href" v-bind="props.action" @click="navigate">
                    <span :class="[item.icon, 'text-color']" />
                    <span class="text-primary font-semibold">{{ item.label }}</span>
                </a>
            </router-link>
            <a v-else :href="item.url" :target="item.target" v-bind="props.action">
                <span class="text-surface-700 dark:text-surface-0">{{ item.label }}</span>
            </a>
        </template>
    </Breadcrumb>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 md:col-span-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-4">Usuario</h5>
                </div>

                <div class="card-body mb-4">
                    <div class="flex items-center justify-center">
                        <Avatar shape="circle"
                            style="width: 100px; height: 100px; background-color: orange; color: white;">
                            <template v-if="row.photo">
                                <Image :src="storage + row.photo"
                                    imageStyle="object-fit: cover; width: 100%; height: 100%; border-radius: 50%;" />
                            </template>
                            <template v-else>
                                <span class="text-5xl font-semibold">
                                    {{ row.persona?.nombres?.substring(0, 1) ??
                                        row.name?.substring(0, 1) }}
                                </span>
                            </template>
                        </Avatar>
                    </div>
                    <div class="text-center">
                        <span class="font-bold">{{ row.persona?.num_doc }}</span>
                        <br>
                        <span>{{ row.persona?.nombre_completo }}</span>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="flex items-center justify-center">
                        <Button label="Editar" class="p-button-primary" :loading="submitted" @click="edit(row)" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha -->
        <div class="col-span-12  md:col-span-9">
            <div v-if="row.id">
                <UserTenantTable ref="elUserTenantTable" :user_id="row.id"></UserTenantTable>
            </div>

            <div v-if="row.id">
                <UserSistemaTable ref="elUserSistemaTable" :user_id="row.id"></UserSistemaTable>
            </div>
        </div>
    </div>

    <Dialog v-model:visible="dialog" :style="{ width: '700px' }" header="Usuario" :modal="true">
        <div class="flex flex-col gap-6">
            <UsersForm ref="elUsersForm"
                @saved="dialog = false; submitted = false; toast.add({ severity: 'success', summary: 'Éxito', detail: 'Operación exitosa', life: 3000 }); getItem();"
                @notsaved="submitted = false">
            </UsersForm>
        </div>
        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" text @click="dialog = false" />
            <Button label="Guardar" icon="pi pi-check" @click="submitted = true; $refs.elUsersForm.save()"
                :loading="submitted" />
        </template>
    </Dialog>

    <Toast />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth.js';
import { useToast } from 'primevue/usetoast';
import { useRouter } from 'vue-router';
import UsersService from '@/services/UsersService.js';
import UserTenantTable from '@/pages/user_tenant/UserTenantTable.vue';
import UserSistemaTable from '@/pages/user_sistema/UserSistemaTable.vue';
import UsersForm from '@/pages/users/UsersForm.vue';

const storage = import.meta.env.VITE_APP_API_URL_STORAGE;

const authStore = useAuthStore();
const toast = useToast();
const router = useRouter();

const props = defineProps({
    userId: { type: String, default: null },
});

//Rutas
const home = ref({
    icon: 'pi pi-users',
    label: 'Usuarios',
    route: '/usuarios',
});
const items = ref([])

//Estados Reactivos
const row = ref({});
const submitted = ref(null);

const dialog = ref(false);
const elUsersForm = ref(null);

//OnMounted
onMounted(async () => {
    getItem();
});

//Métodos
async function getItem() {
    await UsersService.get(props.userId).then(
        (response) => {
            row.value = response;
            items.value.push({ label: row.value.name })
        }
    );
};

function edit(row) {
    dialog.value = true;
    setTimeout(() => {
        elUsersForm.value.setValue(row);
    }, 50);
}
</script>