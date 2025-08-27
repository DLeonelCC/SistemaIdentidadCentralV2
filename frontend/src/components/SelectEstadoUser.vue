<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
    import { ref } from "vue";

    const items = ref([
        { label: 'ACTIVO', value: 'ACTIVO' },
        { label: 'INACTIVO', value: 'INACTIVO' },
    ]);
</script>