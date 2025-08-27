<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'ACCESO FACIL', value: 'ACCESO FACIL' },
    { label: 'ACCESO NORMAL', value: 'ACCESO NORMAL' },
    { label: 'ACCESO INTERMEDIO', value: 'ACCESO INTERMEDIO' },
    { label: 'ACCESO DIFICIL', value: 'ACCESO DIFICIL' },
]);
</script>