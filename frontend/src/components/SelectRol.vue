<template>
    <AutoComplete 
        v-model="model" 
        :suggestions="options" 
        optionLabel="name"
        placeholder="Buscar Rol" 
        forceSelection 
        dropdown 
        class="w-full"
        @complete="search"
        @update:modelValue="updateSelection"
    >
        <template #empty>
            <div class="p-2 text-gray-400">No se han encontrado resultados</div>
        </template>
    </AutoComplete>
</template>

<script setup>
import { ref, onBeforeMount } from 'vue';
import RolesService from '@/services/RolesService.js';

// Emits
const emits = defineEmits(["selectedItem"]);

const props = defineProps({
    sistemaId: { type: Number, default: null, required: true },
});

// Estados reactivos
const options = ref([]);
const model = ref(null);

// OnBeforeMounted
onBeforeMount(async () => {
    const response = await RolesService.getData({ params: { page_size: 25, sort_by: 'id', direction: 'desc', sistema_id: props.sistemaId } });
    options.value = response.data;
});

// Métodos
async function search(event) {
    if (!event.query.trim()) {
        const response = await RolesService.getData({ params: { page_size: 25, sort_by: 'id', direction: 'desc', sistema_id: props.sistemaId } });
        options.value = response.data;
    } else {
        const response = await RolesService.getData({
            params: {
                page_size: 25,
                sort_by: 'id',
                direction: 'desc',
                search: event.query.trim(),
                sistema_id: props.sistemaId 
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

<style scoped>

</style>
