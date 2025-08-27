<template>
    <AutoComplete v-model="model" :suggestions="options" optionLabel="nombre" placeholder="Buscar Cargo" forceSelection
        dropdown class="w-full" @complete="search" @update:modelValue="updateSelection">
        <template #empty>
            <div class="p-2 text-gray-400">No se han encontrado resultados</div>
        </template>
    </AutoComplete>
</template>

<script setup>
    import { ref, onBeforeMount } from 'vue';
    import CargosService from '@/services/CargosService.js';

    // Emits
    const emits = defineEmits(["selectedItem"]);

    const props = defineProps({
        tipo: { type: String, default: null }
    });

    // Estados reactivos
    const options = ref([]);
    const model = ref(null);

    // OnBeforeMounted
    onBeforeMount(async () => {
        const response = await CargosService.getData({ params: { page_size: 25, sort_by: 'id', direction: 'desc', tipo: props.tipo } });
        options.value = response.data;
    });

    // Métodos
    async function search(event) {
        if (!event.query.trim()) {
            const response = await CargosService.getData({ params: { page_size: 25, sort_by: 'id', direction: 'desc', tipo: props.tipo } });
            options.value = response.data;
        } else {
            const response = await CargosService.getData({
                params: {
                    page_size: 25,
                    sort_by: 'id',
                    direction: 'desc',
                    search: event.query.trim(),
                    tipo: props.tipo
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
