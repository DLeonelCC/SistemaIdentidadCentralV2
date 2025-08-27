<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    { label: 'SERVIR 30057', value: 'SERVIR 30057' },
    { label: 'NOMBRADO 276', value: 'NOMBRADO 276' },
    { label: 'CONTRATADO 276', value: 'CONTRATADO 276' },
    { label: 'PRIVADO 728', value: 'PRIVADO 728' },
    { label: 'PRIVADO SC 728', value: 'PRIVADO SC 728' },
    { label: 'CAS 1057', value: 'CAS 1057' },
    { label: 'FAG', value: 'FAG' },
]);
</script>