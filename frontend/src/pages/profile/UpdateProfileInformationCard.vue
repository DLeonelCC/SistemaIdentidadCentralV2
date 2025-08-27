<script setup>
    import { ref } from 'vue';
    import { useToast } from 'primevue/usetoast';
    import { useAuthStore } from '@/stores/auth.js';
    import ProfileService from '@/services/ProfileService.js';

    const toast = useToast();
    const authStore = useAuthStore();

    const storage = import.meta.env.VITE_APP_API_URL_STORAGE;

    const form = ref({
        id: authStore.authUser.id,
        name: authStore.authUser.name,
        email: authStore.authUser.email,
        photo: null
    });
    const errors = ref({});
    const photoPreview = ref(null);
    const photoInput = ref(null);
    const btnLoadings = ref([false, false, false]);

    async function updateProfileInformation(onlyPhoto = 0) {
        btnLoadings.value[0] = onlyPhoto === 0;
        btnLoadings.value[2] = onlyPhoto === 1;
        if (photoInput.value) {
            form.value.photo = photoInput.value.files[0];
        }
        form.value.onlyPhoto = onlyPhoto;
        try {
            const result = await ProfileService.updateProfileInformation(form.value);
            authStore.authUser = result;
            btnLoadings.value = [false, false, false];
            errors.value = {};
            toast.add({
                severity: 'success',
                summary: 'Éxito',
                detail: 'Guardado con Éxito.',
                life: 1500
            });
        } catch (e) {
            btnLoadings.value = [false, false, false];
            errors.value.name = e.response?.data?.errors?.name?.[0] ?? null;
            errors.value.email = e.response?.data?.errors?.email?.[0] ?? null;
            errors.value.photo = e.response?.data?.errors?.photo?.[0] ?? null;
        }
    }

    function selectNewPhoto() {
        photoInput.value.click();
    }

    function updatePhotoPreview() {
        const photo = photoInput.value.files[0];
        if (!photo) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(photo);
    }

    async function deletePhoto() {
        btnLoadings.value[1] = true;
        const result = await ProfileService.deleteProfilePhoto({
            params: {
                user_id: form.value.id
            }
        });
        clearPhotoFileInput();
        authStore.authUser = result;
        btnLoadings.value[1] = false;
        toast.add({
            severity: 'success',
            summary: 'Éxito',
            detail: 'Foto eliminada con éxito.',
            life: 1500
        });
    }

    function clearPhotoFileInput() {
        if (photoInput.value?.value) {
            photoPreview.value = null;
            photoInput.value.value = null;
        }
    }
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">

                <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview" />

                <div class="flex justify-content-end flex-wrap gap-2 mb-3">
                    <Button outlined icon="pi pi-camera" label="Seleccionar Foto" @click="selectNewPhoto" />
                    <Button v-if="authStore.authUser.photo" label="Eliminar Foto" icon="pi pi-trash"
                        @click="deletePhoto" />
                    <Button v-if="photoPreview" label="Actualizar Foto" icon="pi pi-check"
                        @click="updateProfileInformation(1)" :loading="btnLoadings[2]" />
                </div>

                <div class="flex justify-center mb-3">
                    <div v-if="photoPreview">
                        <!-- Si hay una nueva foto seleccionada -->
                        <Avatar :image="photoPreview" size="xlarge" shape="circle"
                            style="width: 150px; height: 150px;" />
                    </div>
                    <div v-else-if="authStore.authUser.photo">
                        <!-- Si hay foto en el backend -->
                        <Avatar :image="storage + authStore.authUser.photo" size="xlarge" shape="circle"
                            style="width: 150px; height: 150px;" />
                    </div>
                    <div v-else>
                        <!-- Si no hay foto ni preview, mostrar letra -->
                        <Avatar :label="authStore.user?.name?.charAt(0)?.toUpperCase()" size="xlarge" shape="circle"
                            style="width: 150px; height: 150px; font-size: 80px; font-weight: bold" />
                    </div>
                </div>

                <p v-if="errors.photo" class="text-center text-red-500">{{ errors.photo }}</p>

                <div class="text-center">
                    <span class="text-lg font-bold">{{ authStore.authUser.name }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-bold mb-3">Información de Usuario</h3>

        <div class="p-3 border-round-2xl mb-3" style="background-color: var(--surface-100); color: var(--text-color)">
            Puede <b>actualizar su nombre de usuario</b> y <b>correo electrónico</b> en este formulario.
            Asegúrese de ingresar una dirección válida, ya que será utilizada para notificaciones del sistema.
            <br />
            Una vez guardados los cambios, será redirigido automáticamente o deberá volver a iniciar sesión si
            es necesario.
        </div>

        <div class="grid grid-cols-12 gap-x-4 gap-y-4">
            <div class="col-span-12 sm:col-span-4 p-1">
                <InputText v-model="form.name" placeholder="Nombre Usuario" class="w-full"
                    :class="{ 'p-invalid': errors.name }" />
                <small v-if="errors.name" class="p-error">{{ errors.name }}</small>
            </div>
            <div class="col-span-12 sm:col-span-4 p-1">
                <InputText v-model="form.email" placeholder="Email" class="w-full"
                    :class="{ 'p-invalid': errors.email }" />
                <small v-if="errors.email" class="p-error">{{ errors.email }}</small>
            </div>
        </div>

        <div class="pt-3">
            <Button label="Guardar Cambios" icon="pi pi-check" @click="updateProfileInformation()"
                :loading="btnLoadings[0]" />
        </div>
    </div>

    <Toast />
</template>
