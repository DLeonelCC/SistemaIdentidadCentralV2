<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'AFP', value: 'AFP' },
    { label: 'ONP', value: 'ONP' },
    { label: 'NO AFIL.', value: 'NO AFIL.' },
    { label: 'RETIRO_95.5', value: 'RETIRO_95.5' },
]);
</script>