<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'PENDIENTE', value: 'PENDIENTE' },
    { label: 'PARALIZADO', value: 'PARALIZADO' },
    { label: 'EN EJECUCION', value: 'EN EJECUCION' },
    { label: 'CULMINADO', value: 'CULMINADO' },
]);
</script>