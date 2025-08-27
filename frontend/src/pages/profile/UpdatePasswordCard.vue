<script setup>
    import { ref } from 'vue';
    import { useToast } from 'primevue/usetoast';
    import { useAuthStore } from '../../stores/auth';
    import ProfileService from '@/services/ProfileService.js';

    const toast = useToast();
    const authStore = useAuthStore();

    //Estados reactivos
    const form = ref({
        id: authStore.authUser.id,
        current_password: null,
        password: null,
        password_confirmation: null,
    });
    const errors = ref({});
    const btnLoadings = ref([false]);

    //Métodos
    async function updatePassword() {
        btnLoadings.value[0] = true;
        try {
            await ProfileService.updatePassword(form.value);
            btnLoadings.value[0] = false;
            errors.value = {};
            toast.add({
                severity: 'success',
                summary: 'Éxito',
                detail: 'Guardado con Éxito.',
                life: 1500
            });
        } catch (e) {
            btnLoadings.value[0] = false;
            errors.value.current_password = e.response?.data?.errors?.current_password?.[0] ?? null;
            errors.value.password = e.response?.data?.errors?.password?.[0] ?? null;
            errors.value.password_confirmation = e.response?.data?.errors?.password_confirmation?.[0] ?? null;
        }
    }
</script>

<template>
    <div class="card">
        <h3 class="text-lg font-bold mb-3">Cambiar Contraseña</h3>

        <!-- Banner -->
        <div class="p-3 border-round-2xl mb-3" style="background-color: var(--surface-100); color: var(--text-color)">
            Para <b>cambiar su contraseña</b>, debe ingresar su <b>contraseña actual</b> y luego escribir dos
            veces la <b>nueva contraseña</b> para confirmar que coincidan.
            <br />
            Asegúrese de elegir una contraseña segura. Después de guardar los cambios, es posible que se le
            solicite volver a iniciar sesión.
        </div>

        <!-- Campos -->
        <div class="grid grid-cols-12 gap-x-4 gap-y-4">
            <div class="col-span-12 md:col-span-4 p-1">
                <InputText type="password" v-model="form.current_password" placeholder="Contraseña actual"
                    class="w-full" :class="{ 'p-invalid': errors.current_password }" />
                <small v-if="errors.current_password" class="p-error">{{ errors.current_password }}</small>
            </div>

            <div class="col-span-12 md:col-span-4 p-1">
                <InputText type="password" v-model="form.password" placeholder="Nueva contraseña" class="w-full"
                    :class="{ 'p-invalid': errors.password }" />
                <small v-if="errors.password" class="p-error">{{ errors.password }}</small>
            </div>

            <div class="col-span-12 md:col-span-4 p-1">
                <InputText type="password" v-model="form.password_confirmation" placeholder="Confirmar contraseña"
                    class="w-full" :class="{ 'p-invalid': errors.password_confirmation }" />
                <small v-if="errors.password_confirmation" class="p-error">{{ errors.password_confirmation
                    }}</small>
            </div>
        </div>

        <!-- Botón -->
        <div class="pt-2">
            <Button label="Guardar Cambios" icon="pi pi-check" @click="updatePassword()" :loading="btnLoadings[0]" />
        </div>
    </div>

    <Toast />
</template>