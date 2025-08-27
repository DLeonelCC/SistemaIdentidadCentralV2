<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-4">
            <FormInput v-model="form.siglas" id="siglas" label="Siglas" :error="errors.siglas" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-8">
            <FormInput v-model="form.nombre" id="nombre" label="Nombre" :error="errors.nombre" :autofocus="false"
                :required="false" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import TenantsService from '@/services/TenantsService.js';
import FormInput from '../../components/FormInput.vue';

//Emits
const emits = defineEmits(["saved", "notsaved"]);

const props = defineProps({
    //
});

//Estados reactivos
const form = ref({
    id: null,
    siglas: null,
    nombre: null,
});
const errors = ref({});

//onMounted
onMounted(() => {
    reset();
});

async function save() {
    try {
        await TenantsService.save(form.value);
        emits('saved');
    } catch (e) {
        console.log(e.response.data.errors)
        setErrors(e.response.data.errors);
        emits('notsaved');
    }
};

function setValue(row) {
    form.value = row;
};

function setErrors(row) {
    errors.value.siglas = row.siglas ? row.siglas[0] : null;
    errors.value.nombre = row.nombre ? row.nombre[0] : null;
};

function reset() {
    form.value = {};
    errors.value = {};
};

//Expose
defineExpose({
    form,
    setValue,
    setErrors,
    save,
    reset
});
</script>
