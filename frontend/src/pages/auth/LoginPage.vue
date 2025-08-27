<template>
    <div class="flex h-screen w-full">
        <div class="w-full p-4">
            <div class="flex flex-col md:flex-row h-full rounded-xl">
                <!-- Imagen -->
                <div
                    class="w-full md:w-3/4 flex flex-col items-center justify-between p-8 gap-6 bg-surface-100 rounded-xl border border-surface-200 dark:bg-surface-900 dark:border-surface-700">
                    <!-- Theme Switcher -->
                    <div class="w-full flex justify-between">
                        <span class="inline-flex items-center gap-1">
                            <span class="inline-flex items-center gap-1">
                                <svg width="35" height="40" viewBox="0 0 35 40" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="h-8">
                                    <path
                                        d="M25.87 18.05L23.16 17.45L25.27 20.46V29.78L32.49 23.76V13.53L29.18 14.73L25.87 18.04V18.05ZM25.27 35.49L29.18 31.58V27.67L25.27 30.98V35.49ZM20.16 17.14H20.03H20.17H20.16ZM30.1 5.19L34.89 4.81L33.08 12.33L24.1 15.67L30.08 5.2L30.1 5.19ZM5.72 14.74L2.41 13.54V23.77L9.63 29.79V20.47L11.74 17.46L9.03 18.06L5.72 14.75V14.74ZM9.63 30.98L5.72 27.67V31.58L9.63 35.49V30.98ZM4.8 5.2L10.78 15.67L1.81 12.33L0 4.81L4.79 5.19L4.8 5.2ZM24.37 21.05V34.59L22.56 37.29L20.46 39.4H14.44L12.34 37.29L10.53 34.59V21.05L12.42 18.23L17.45 26.8L22.48 18.23L24.37 21.05ZM22.85 0L22.57 0.69L17.45 13.08L12.33 0.69L12.05 0H22.85Z"
                                        fill="var(--p-primary-color)" />
                                    <path
                                        d="M30.69 4.21L24.37 4.81L22.57 0.69L22.86 0H26.48L30.69 4.21ZM23.75 5.67L22.66 3.08L18.05 14.24V17.14H19.7H20.03H20.16H20.2L24.1 15.7L30.11 5.19L23.75 5.67ZM4.21002 4.21L10.53 4.81L12.33 0.69L12.05 0H8.43002L4.22002 4.21H4.21002ZM21.9 17.4L20.6 18.2H14.3L13 17.4L12.4 18.2L12.42 18.23L17.45 26.8L22.48 18.23L22.5 18.2L21.9 17.4ZM4.79002 5.19L10.8 15.7L14.7 17.14H14.74H15.2H16.85V14.24L12.24 3.09L11.15 5.68L4.79002 5.2V5.19Z"
                                        fill="var(--p-text-color)" />
                                </svg>
                                <span class="text-xl font-semibold">IDENT<span class="text-primary">IA</span></span>
                            </span>
                        </span>
                        <ThemeSwitcher />
                    </div>

                    <!-- Imagen-->
                    <div class="hidden md:block">
                        <Image :src="serverImage" alt="Login Image" width="400" preview />
                    </div>


                    <!-- Texto -->
                    <span class="text-center text-gray-700 dark:text-gray-300 font-medium">
                        PRIVATE SERVER
                    </span>
                </div>

                <!-- Formulario -->
                <div class="w-full md:w-1/4 py-8 md:p-8 lg:p-16 flex flex-col justify-center gap-6">
                    <h2 class="text-3xl font-bold text-primary">Bienvenido a Identia</h2>
                    <p class="text-md text-gray-400">Por favor, ingrese sus credenciales para acceder al sistema. El
                        acceso está restringido únicamente al personal autorizado.</p>

                    <div>
                        <FloatLabel>
                            <InputText id="email" v-model="form.email" class="w-full"
                                :class="{ 'p-invalid': errors.email[0] }" />
                            <label for="email" class="block text-gray-600 mb-2">Correo Electrónico</label>
                        </FloatLabel>
                        <small v-if="errors.email[0]" class="text-red-500">{{ errors.email[0] }}</small>
                    </div>

                    <div>
                        <FloatLabel>
                            <Password id="password" v-model="form.password" class="w-full" inputClass="w-full"
                                toggleMask feedback />
                            <label for="password">Contraseña</label>
                        </FloatLabel>
                        <small v-if="errors.password[0]" class="text-red-500">{{ errors.password[0] }}</small>
                    </div>

                    <Button label="Ingresar" icon="pi pi-sign-in" class="w-full" :loading="progress.loading"
                        @click="login" />

                    <p v-if="statusText" class="text-center text-gray-500 mt-4">{{ statusText }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref } from "vue";
    import { useAuthStore } from '@/stores/auth.js';
    import { useRouter } from 'vue-router';
    import Button from 'primevue/button';
    import InputText from 'primevue/inputtext';
    import Password from 'primevue/password';
    import ThemeSwitcher from "../../components/ThemeSwitcher.vue";
    import Image from 'primevue/image';
    import serverImage from '@/assets/imgs/server.svg';

    const authStore = useAuthStore();
    const router = useRouter();

    const progress = ref({ loading: false, portentage: 0 });
    const statusText = ref(null);

    const form = ref({
        email: "",
        password: "",
    });

    const errors = ref({
        email: [null],
        password: [null],
    });

    const login = async () => {
        progress.value.loading = true;
        statusText.value = 'Verificando datos...';
        errors.value.email = [null];
        errors.value.password = [null];

        await authStore.login(form.value);
        console.log(authStore.authErrors);
        if (authStore.authErrors.email || authStore.authErrors.password) {
            errors.value.email = authStore.authErrors.email ?? [null];
            errors.value.password = authStore.authErrors.password ?? [null];
            statusText.value = null;
        } else {
            statusText.value = authStore.authErrors === 'Unauthorized'
                ? 'No existe ningún registro con estas credenciales.'
                : 'Redireccionando...';
        }

        progress.value.loading = false;
    };
</script>

<style scoped>
    .p-invalid {
        border-color: #f87171;
        /* rojo claro */
    }
</style>
