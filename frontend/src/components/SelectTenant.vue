<template>
    <AutoComplete v-model="model" :suggestions="options" optionLabel="nombre" placeholder="Buscar Tenant" forceSelection
        dropdown class="w-full" @complete="search" @update:modelValue="updateSelection">
        <template #empty>
            <div class="p-2 text-gray-400">No se han encontrado resultados</div>
        </template>
    </AutoComplete>
</template>

<script setup>
    import { ref, onBeforeMount } from 'vue';
    import TenantsService from '@/services/TenantsService.js';

    // Emits
    const emits = defineEmits(["selectedItem"]);

    // Estados reactivos
    const options = ref([]);
    const model = ref(null);

    // OnBeforeMounted
    onBeforeMount(async () => {
        const response = await TenantsService.getData({ params: { page_size: 25, sort_by: 'id', direction: 'desc' } });
        options.value = response.data;
    });

    // Métodos
    async function search(event) {
        if (!event.query.trim()) {
            const response = await TenantsService.getData({ params: { page_size: 25, sort_by: 'id', direction: 'desc' } });
            options.value = response.data;
        } else {
            const response = await TenantsService.getData({
                params: {
                    page_size: 25,
                    sort_by: 'id',
                    direction: 'desc',
                    search: event.query.trim()
                }
            });
            options.value = response.data;
        }
    }

    function updateSelection(event) {
        if (event) {
            model.value = event;
            emits('selectedItem', event);
        } else {
            model.value = null;
        }
    }

    function reset() {
        model.value = null;
    }

    function updateProp(item) {
        model.value = item;
    }

    // Expose
    defineExpose({
        reset,
        updateProp
    });
</script>

<style scoped></style>
