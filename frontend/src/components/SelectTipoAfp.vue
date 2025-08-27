<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'HÁBITAT', value: 'HÁBITAT' },
    { label: 'INTEGRA', value: 'INTEGRA' },
    { label: 'PROFUTURO', value: 'PROFUTURO' },
    { label: 'PRIMA', value: 'PRIMA' },
]);
</script>