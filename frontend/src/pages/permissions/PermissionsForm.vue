<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6">
            <FormInput v-model="form.name" id="name" label="Nombre" :error="errors.name" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-6">
            <FormInput v-model="form.guard_name" id="guard_name" label="Guard Name" :error="errors.guard_name"
                :autofocus="false" :required="false" />
        </div>
        <div class="col-span-6">
            <FormInput v-model="form.label" id="label" label="Label" :error="errors.label" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-6">
            <FormInput v-model="form.module" id="module" label="Modulo" :error="errors.module" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-12">
            <label for="sistema_id" class="block font-bold mb-3">Sistema</label>
            <SelectSistema ref="elSelectSistema" @selectedItem="onSistemaSelected" required="true"
                :invalid="errors.sistema_id != null" />
            <small v-if="errors.sistema_id" class="text-red-500">{{ errors.sistema_id }}</small>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import PermissionsService from '@/services/PermissionsService.js';
import SelectSistema from '../../components/SelectSistema.vue';
import FormInput from '../../components/FormInput.vue';

//Emits
const emits = defineEmits(["saved", "notsaved"]);

const props = defineProps({
    //
});

//Estados reactivos
const form = ref({
    id: null,
    name: null,
    guard_name: null,
    label: null,
    module: null,
    sistema_id: null,
});
const errors = ref({});

const elSelectSistema = ref(null);

//onMounted
onMounted(() => {
    reset();
});

async function save() {
    try {
        await PermissionsService.save(form.value);
        emits('saved');
    } catch (e) {
        console.log(e.response.data.errors)
        setErrors(e.response.data.errors);
        emits('notsaved');
    }
};

function setValue(row) {
    form.value = row;
    if (row.sistema) { elSelectSistema.value.updateProp(row.sistema); }
};

function setErrors(row) {
    errors.value.name = row.name ? row.name[0] : null;
    errors.value.guard_name = row.guard_name ? row.guard_name[0] : null;
    errors.value.label = row.label ? row.label[0] : null;
    errors.value.module = row.module ? row.module[0] : null;
    errors.value.sistema_id = row.sistema_id ? row.sistema_id[0] : null;
};

function reset() {
    form.value = {};
    errors.value = {};
};

function onSistemaSelected(item) {
    form.value.sistema_id = item.id;
}

//Expose
defineExpose({
    form,
    setValue,
    setErrors,
    save,
    reset
});
</script>
