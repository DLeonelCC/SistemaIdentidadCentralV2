<template>
    <div class="flex h-screen">
        <div class="hidden lg:block w-72 p-4 fixed top-0 left-0 bottom-0 z-10 bg-surface">
            <div
                class="flex flex-col h-full bg-surface-100 rounded-xl border border-surface-200 p-2 dark:bg-surface-900 dark:border-surface-700">
                <!-- Logo -->
                <div class="px-2">
                    <span class="inline-flex items-center gap-1 px-2 py-2">
                        <span class="inline-flex items-center gap-1 px-2 py-2">
                            <svg width="35" height="40" viewBox="0 0 35 40" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-8">
                                <path
                                    d="M25.87 18.05L23.16 17.45L25.27 20.46V29.78L32.49 23.76V13.53L29.18 14.73L25.87 18.04V18.05ZM25.27 35.49L29.18 31.58V27.67L25.27 30.98V35.49ZM20.16 17.14H20.03H20.17H20.16ZM30.1 5.19L34.89 4.81L33.08 12.33L24.1 15.67L30.08 5.2L30.1 5.19ZM5.72 14.74L2.41 13.54V23.77L9.63 29.79V20.47L11.74 17.46L9.03 18.06L5.72 14.75V14.74ZM9.63 30.98L5.72 27.67V31.58L9.63 35.49V30.98ZM4.8 5.2L10.78 15.67L1.81 12.33L0 4.81L4.79 5.19L4.8 5.2ZM24.37 21.05V34.59L22.56 37.29L20.46 39.4H14.44L12.34 37.29L10.53 34.59V21.05L12.42 18.23L17.45 26.8L22.48 18.23L24.37 21.05ZM22.85 0L22.57 0.69L17.45 13.08L12.33 0.69L12.05 0H22.85Z"
                                    fill="var(--p-primary-color)" />
                                <path
                                    d="M30.69 4.21L24.37 4.81L22.57 0.69L22.86 0H26.48L30.69 4.21ZM23.75 5.67L22.66 3.08L18.05 14.24V17.14H19.7H20.03H20.16H20.2L24.1 15.7L30.11 5.19L23.75 5.67ZM4.21002 4.21L10.53 4.81L12.33 0.69L12.05 0H8.43002L4.22002 4.21H4.21002ZM21.9 17.4L20.6 18.2H14.3L13 17.4L12.4 18.2L12.42 18.23L17.45 26.8L22.48 18.23L22.5 18.2L21.9 17.4ZM4.79002 5.19L10.8 15.7L14.7 17.14H14.74H15.2H16.85V14.24L12.24 3.09L11.15 5.68L4.79002 5.2V5.19Z"
                                    fill="var(--p-text-color)" />
                            </svg>
                            <span class="text-xl font-semibold">IDEN<span class="text-primary">TIA</span></span>
                        </span>
                    </span>
                </div>

                <!-- Menu con scroll -->
                <div class="flex-1 overflow-y-auto">
                    <Menu :model="items" class="custom-menu">
                        <template #submenulabel="{ item }">
                            <span class="text-primary font-bold">{{ item.label }}</span>
                        </template>
                        <template #item="{ item, props }">
                            <router-link v-slot="{ href, navigate }" :to="item.route" custom>
                                <a v-ripple :href="href" class="flex items-center text-gray-400" v-bind="props.action"
                                    :class="{ 'bg-primary rounded-xl': isActive(item), }"
                                    :inert="props.action['aria-hidden'] === 'true' ? true : null"
                                    @focus="(e) => { if (e.target.getAttribute('aria-hidden') === 'true') e.target.blur(); }">
                                    <span
                                        :class="[item.icon, { 'text-white': isActive(item), 'dark:text-black': isActive(item) }]" />
                                    <span :class="{ 'text-white': isActive(item), 'dark:text-black': isActive(item) }">
                                        {{ item.label }}
                                    </span>
                                    <Badge v-if="item.badge" class="ml-auto" :value="item.badge" />
                                    <span v-if="item.shortcut"
                                        class="ml-auto border border-surface rounded bg-emphasis text-muted-color text-xs p-1">
                                        {{ item.shortcut }}
                                    </span>
                                </a>
                            </router-link>
                        </template>
                    </Menu>
                </div>

                <!-- Avatar fijo al fondo -->
                <div class="px-2">
                    <div @click="visibleRight = true"
                        class="flex items-center p-2 hover:bg-primary rounded cursor-pointer">
                        <span class="pi pi-cog mr-2" />
                        <span>Configuración</span>
                    </div>

                    <div class="px-2">
                        <Divider />
                    </div>

                    <button v-ripple @click="$router.push('/profile')"
                        class="relative overflow-hidden w-full border-0 bg-transparent flex items-start p-2 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-none cursor-pointer transition-colors duration-200">
                        <Avatar v-if="authStore.user?.photo" :image="storage + authStore.user.photo" class="mr-2"
                            shape="circle" />
                        <Avatar v-else :label="authStore.user?.name?.charAt(0).toUpperCase()" class="mr-2"
                            shape="circle" />
                        <span class="inline-flex flex-col items-start">
                            <span class="font-bold">{{ authStore.user.name }}</span>
                            <span class="text-sm">{{ authStore.user.email }}</span>
                        </span>
                    </button>
                    <div class="flex items-center gap-2 py-2">
                        <Button label="Salir" icon="pi pi-sign-out" class="flex-auto" severity="danger" outlined
                            @click="logout"></Button>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:ml-[17rem] p-4">
            <div class="block sm:block lg:hidden">
                <Menubar class="mb-4">
                    <template #start>
                        <div class="block sm:block lg:hidden">
                            <Button icon="pi pi-bars" text @click="visible = true" />
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-2">
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
                    </template>
                    <template #end>
                        <ThemeSwitcher />
                    </template>
                </Menubar>
            </div>

            <!-- Contenido principal -->
            <RouterView />
        </div>
    </div>

    <Drawer v-model:visible="visibleRight" header="Configuración" position="right">
        <ThemeSwitcherPanel />
    </Drawer>

    <Drawer v-model:visible="visible">
        <template #container="{ closeCallback }">
            <div class="flex flex-col h-full p-4 bg-surface-100 dark:bg-surface-900 dark:border-surface-700">
                <div class="flex items-center justify-between p-2 shrink-0">
                    <span class="inline-flex items-center gap-1 px-2 py-2">
                        <span class="inline-flex items-center gap-1 px-2 py-2">
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
                    <span>
                        <Button type="button" @click="closeCallback" icon="pi pi-times" rounded outlined></Button>
                    </span>
                </div>

                <!-- Menu con scroll -->
                <div class="flex-1 overflow-y-auto">
                    <Menu :model="items" class="custom-menu">
                        <template #submenulabel="{ item }">
                            <span class="text-primary font-bold">{{ item.label }}</span>
                        </template>
                        <template #item="{ item, props }">
                            <router-link v-slot="{ href, navigate }" :to="item.route" custom>
                                <a v-ripple :href="href" class="flex items-center text-gray-400" v-bind="props.action"
                                    :class="{ 'bg-primary rounded-xl': isActive(item), }"
                                    :inert="props.action['aria-hidden'] === 'true' ? true : null"
                                    @focus="(e) => { if (e.target.getAttribute('aria-hidden') === 'true') e.target.blur(); }">
                                    <span
                                        :class="[item.icon, { 'text-white': isActive(item), 'dark:text-black': isActive(item) }]" />
                                    <span :class="{ 'text-white': isActive(item), 'dark:text-black': isActive(item) }">
                                        {{ item.label }}
                                    </span>
                                    <Badge v-if="item.badge" class="ml-auto" :value="item.badge" />
                                    <span v-if="item.shortcut"
                                        class="ml-auto border border-surface rounded bg-emphasis text-muted-color text-xs p-1">
                                        {{ item.shortcut }}
                                    </span>
                                </a>
                            </router-link>
                        </template>
                    </Menu>
                </div>

                <!-- Avatar fijo al fondo -->
                <div class="px-2">
                    <div @click="visibleRight = true"
                        class="flex items-center p-2 hover:bg-surface-100 rounded cursor-pointer">
                        <span class="pi pi-cog mr-2" />
                        <span>Configuración</span>
                    </div>

                    <div class="px-2">
                        <Divider />
                    </div>

                    <button v-ripple @click="$router.push('/profile')"
                        class="relative overflow-hidden w-full border-0 bg-transparent flex items-start p-2 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-none cursor-pointer transition-colors duration-200">
                        <Avatar v-if="authStore.user?.photo" :image="storage + authStore.user.photo" class="mr-2"
                            shape="circle" />
                        <Avatar v-else :label="authStore.user?.name?.charAt(0).toUpperCase()" class="mr-2"
                            shape="circle" />
                        <span class="inline-flex flex-col items-start">
                            <span class="font-bold">{{ authStore.user.name }}</span>
                            <span class="text-sm">{{ authStore.user.email }}</span>
                        </span>
                    </button>
                    <div class="flex items-center gap-2 py-2">
                        <Button label="Salir" icon="pi pi-sign-out" class="flex-auto" severity="danger" outlined
                            @click="logout"></Button>
                    </div>
                </div>
            </div>
        </template>
    </Drawer>
</template>

<script setup>
    import { ref } from 'vue';
    import { RouterView } from 'vue-router';
    import { useAuthStore } from '@/stores/auth.js';
    import { useRoute } from 'vue-router';
    import Cookies from "js-cookie";
    import ThemeSwitcher from '../components/ThemeSwitcher.vue';
    import ThemeSwitcherPanel from '@/components/ThemeSwitcherPanel.vue';

    const authStore = useAuthStore();
    const router = useRoute();

    const storage = import.meta.env.VITE_APP_API_URL_STORAGE;

    //Validando Token de acceso
    authStore.validateToken();

    //Cargando Cookie en caso no exista del authStore
    if (authStore.token && authStore.token != Cookies.get('authToken')) {
        Cookies.remove('authToken');
        Cookies.set('authToken', authStore.token);
        window.location.reload();
    };

    const visible = ref(false);
    const op = ref();
    const visibleRight = ref(false);

    // Función que verifica si un ítem está activo
    function isActive(item) {
        return router.path === item.route;
    }

    const items = ref([
        {
            label: 'Dashboard',
            icon: 'pi pi-home',
            route: '/dashboard'
        },
        {
            label: 'Accesos',
            icon: 'pi pi-envelope',
            items: [
                { label: 'Usuarios', icon: 'pi pi-users', route: '/usuarios' },
                { label: 'Roles', icon: 'pi pi-sitemap', route: '/roles' },
                { label: 'Permisos', icon: 'pi pi-shield', route: '/permisos' },
            ]
        },
        {
            label: 'Servicios',
            icon: 'pi pi-sitemap',
            items: [
                {
                    label: 'Servicios',
                    icon: 'pi pi-server',
                    route: '/servicios'
                },
                {
                    label: 'Archivos',
                    icon: 'pi pi-folder',
                    route: '/archivos'
                }
            ]
        },
        {
            label: 'Empresa',
            icon: 'pi pi-building',
            items: [
                { label: 'Empresa', icon: 'pi pi-building', route: '/empresa' },
            ]
        },
    ]);

    const logout = async () => {
        authStore.logout();
    }
</script>

<style>
    .custom-menu {
        --p-menu-border-color: transparent;
        --p-menu-background: transparent;
    }
</style>