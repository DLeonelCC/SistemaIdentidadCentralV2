<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'MIXTO', value: 'MIXTO' },
    { label: 'FLUJO', value: 'FLUJO' },
    { label: 'NINGUNO', value: 'NINGUNO' },
]);
</script>