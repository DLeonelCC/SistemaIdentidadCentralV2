<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'PERSONAL CON CARGO A COSTO DIRECTO', value: 'PERSONAL CON CARGO A COSTO DIRECTO' },
    { label: 'GASTOS GENERALES', value: 'GASTOS GENERALES' },
    { label: 'SUPERVISION DE PROYECTOS DE INVERSION', value: 'SUPERVISION DE PROYECTOS DE INVERSION' },
    { label: 'LIQUIDACION DE PROYECTOS DE INVERSION', value: 'LIQUIDACION DE PROYECTOS DE INVERSION' },
    { label: 'ELABORACION DE ESTUDIOS DE PRE INVERSION Y EXPEDIENTES TECNICOS', value: 'ELABORACION DE ESTUDIOS DE PRE INVERSION Y EXPEDIENTES TECNICOS' },
    { label: 'GESTION DE PROYECTOS - MONITOREO DE INVERSIONES', value: 'GESTION DE PROYECTOS - MONITOREO DE INVERSIONES' },
    { label: 'SEGUIMIENTO DE PROYECTOS DE INVERSION', value: 'SEGUIMIENTO DE PROYECTOS DE INVERSION' },
]);
</script>