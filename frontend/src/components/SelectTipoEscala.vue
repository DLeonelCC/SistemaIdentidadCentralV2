<template>
    <Select :options="items" optionLabel="label" optionValue="value" placeholder="Selecciona una opción" checkmark
        :highlightOnSelect="false" class="w-full" />
</template>

<script setup>
    import { ref } from "vue";

    const items = ref([
        { label: 'ESCALA 2017 - REG-373-2017-GR PUNO', value: 'ESCALA 2017 - REG-373-2017-GR PUNO' },
        { label: 'ESCALA 2023 - REG-374-2023-GR PUNO', value: 'ESCALA 2023 - REG-374-2023-GR PUNO' },
    ]);
</script>