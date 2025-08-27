<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'RECURSOS ORDINARIOS', value: 'RECURSOS ORDINARIOS' },
    { label: 'RECURSOS DIRECTAMENTE RECAUDADOS', value: 'RECURSOS DIRECTAMENTE RECAUDADOS' },
    { label: 'RECURSOS DETERMINADOS', value: 'RECURSOS DETERMINADOS' },
    { label: 'RECURSOS POR OPERACIONES OFICIALES DE CREDITO', value: 'RECURSOS POR OPERACIONES OFICIALES DE CREDITO' },
    { label: 'DONACIONES Y TRANSFERENCIAS', value: 'DONACIONES Y TRANSFERENCIAS' },
]);
</script>