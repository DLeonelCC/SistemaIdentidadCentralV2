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

    <RouterView />
</template>
<script setup>
import { ref, onMounted } from 'vue';

const tables = ref([
    {
        name: 'sistemas',
        label: 'sistemas',
    },
    {
        name: 'tenants',
        label: 'tenants',
    },
    {
        name: 'personas',
        label: 'Personas',
    },
    {
        name: 'personals',
        label: 'Personal',
    },
    {
        name: 'sedes',
        label: 'Sedes',
    },
    {
        name: 'oficinas',
        label: 'Oficinas',
    },
    {
        name: 'proyectos',
        label: 'Proyectos',
    },
    {
        name: 'cargos',
        label: 'Cargos',
    },
]);

const home = ref({
    icon: 'pi pi-server',
    label: 'Servicios',
    route: '/servicios',
});

const items = ref([]);

onMounted(() => {
    const currentRoute = window.location.pathname.split('/').pop();
    const table = tables.value.find(table => table.name === currentRoute);
    if (table) {
        items.value.push({
            label: table.label,
        });
    }
});

</script>