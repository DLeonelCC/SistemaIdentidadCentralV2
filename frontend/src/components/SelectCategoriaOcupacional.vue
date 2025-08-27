<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'FUNCIONARIO', value: 'FUNCIONARIO' },
    { label: 'PROFESIONAL', value: 'PROFESIONAL' },
    { label: 'TÉCNICO', value: 'TÉCNICO' },
    { label: 'AUXILIAR', value: 'AUXILIAR' },
]);
</script>