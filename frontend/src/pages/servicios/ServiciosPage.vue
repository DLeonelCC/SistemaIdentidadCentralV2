<template>
    <div>
        <div class="card">
            <div class="flex flex-wrap items-start justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-semibold mb-2">Servicios</h1>
                    <p class="text-lg text-gray-500">
                        Módulos y recursos compartidos que permiten integrar y reutilizar información entre los
                        diferentes sistemas conectados.
                    </p>
                </div>
                <div>
                    <!------>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div v-for="(card, index) in cards" :key="index"
                    class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3">
                    <div class="card h-full flex flex-col justify-between border rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <i class="text-primary text-xl" :class="card.icon"></i>
                                <strong class="text-lg text-primary">{{ card.title }}</strong>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-6">
                            {{ card.description }}
                        </p>
                        <p class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">
                            {{ card.count }} registros encontrados
                        </p>
                        <div class="mt-auto text-right">
                            <Button label="Acceder" icon="las la-door-open" class="px-4 py-2 rounded-lg" outlined
                                @click="$router.push(card.route)" />
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';

    const cards = ref([
        {
            title: 'Tabla de Sistemas',
            description: 'Consulte y gestione los sistemas registrados que se integran mediante el IDP de forma centralizada.',
            icon: 'pi pi-desktop',
            route: { name: 'sistemas' },
            count: 0,
        },
        {
            title: 'Tabla de Tenants',
            description: 'Consulte y administre las organizaciones o entidades que utilizan el servicio de autenticación del IDP.',
            icon: 'pi pi-building',
            route: { name: 'tenants' },
            count: 0,
        },
        {
            title: 'Tabla de Personas',
            description: 'Aquí podrá consultar y gestionar la información de las personas registradas, de forma clara y organizada.',
            icon: 'pi pi-id-card',
            route: { name: 'personas' },
            count: 0,
        },
        {
            title: 'Tabla de Personal',
            description: 'Aquí podrá consultar y gestionar la información del personal registrado, de forma clara y organizada.',
            icon: 'pi pi-briefcase',
            route: { name: 'personals' },
            count: 0,
        },
        {
            title: 'Tabla de Sedes',
            description: 'Aquí podrá consultar y gestionar la información de las sedes registradas, de forma clara y organizada.',
            icon: 'pi pi-map-marker',
            route: { name: 'sedes' },
            count: 0,
        },
        {
            title: 'Tabla de Oficinas',
            description: 'Aquí podrá consultar y gestionar la información de las oficinas registradas, de forma clara y organizada.',
            icon: 'pi pi-box',
            route: { name: 'oficinas' },
            count: 0,
        },
        {
            title: 'Tabla de Proyectos',
            description: 'Aquí podrá consultar y gestionar la información de los proyectos registrados, de forma clara y organizada.',
            icon: 'pi pi-folder-open',
            route: { name: 'proyectos' },
            count: 0,
        },
        {
            title: 'Tabla de Cargos',
            description: 'Aquí podrá consultar y gestionar la información de los cargos registrados, de forma clara y organizada.',
            icon: 'pi pi-verified',
            route: { name: 'cargos' },
            count: 0,
        },
    ]);

    onMounted(() => {
        //
    });
</script>