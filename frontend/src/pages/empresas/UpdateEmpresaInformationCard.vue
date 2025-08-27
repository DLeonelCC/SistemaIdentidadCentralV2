<template>
    <div class="card">
        <div class="flex flex-wrap items-start justify-between mb-4">
            <div>
                <h1 class="text-3xl font-semibold mb-2">Empresa</h1>
                <p class="text-lg text-gray-500">Empresa, aqui se muestran los datos de la empresa.</p>
            </div>
            <div>
                <Button label="Guardar Cambios" icon="pi pi-save" @click="updateEmpresaInformation()"
                :loading="btnLoadings[0]" />
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-4">
                <InputText v-model="form.ruc" label="RUC" placeholder="RUC" :class="{ 'p-invalid': errors.ruc }"
                    class="w-full" />
                <small v-if="errors.ruc" class="p-error">{{ errors.ruc }}</small>
            </div>

            <div class="col-span-12 md:col-span-4">
                <InputText v-model="form.razon_social" label="Razón Social" placeholder="Razón Social"
                    :class="{ 'p-invalid': errors.razon_social }" class="w-full" />
                <small v-if="errors.razon_social" class="p-error">{{ errors.razon_social }}</small>
            </div>

            <div class="col-span-12 md:col-span-4">
                <InputText v-model="form.email" label="Email" placeholder="Email" :class="{ 'p-invalid': errors.email }"
                    class="w-full" />
                <small v-if="errors.email" class="p-error">{{ errors.email }}</small>
            </div>
        </div>
    </div>

    <Toast />
</template>

<script setup>
    import { ref } from 'vue';
    import { useToast } from 'primevue/usetoast';
    import { useAuthStore } from '@/stores/auth.js';
    import EmpresaService from '@/services/EmpresaService.js';

    const toast = useToast();
    const authStore = useAuthStore();

    // Estados reactivos
    const form = ref({
        ruc: authStore.authEmpresa.ruc,
        razon_social: authStore.authEmpresa.razon_social,
        email: authStore.authEmpresa.email,
        descripcion: authStore.authEmpresa.descripcion,
    });
    const errors = ref({});
    const btnLoadings = ref([false]);

    // Métodos
    async function updateEmpresaInformation() {
        btnLoadings.value[0] = true;
        try {
            const result = await EmpresaService.updateEmpresaInformation(form.value);
            authStore.authEmpresa = result;
            btnLoadings.value[0] = false;
            errors.value = {};
            toast.add({
                severity: 'success',
                summary: 'Éxito',
                detail: 'Guardado con éxito.',
                life: 1500,
                position: 'top-right'
            });
        } catch (e) {
            btnLoadings.value[0] = false;
            errors.value.ruc = e.response?.data?.errors?.ruc ? e.response.data.errors.ruc[0] : null;
            errors.value.razon_social = e.response?.data?.errors?.razon_social ? e.response.data.errors.razon_social[0] : null;
            errors.value.email = e.response?.data?.errors?.email ? e.response.data.errors.email[0] : null;
            errors.value.descripcion = e.response?.data?.errors?.descripcion ? e.response.data.errors.descripcion[0] : null;
        }
    }
</script>