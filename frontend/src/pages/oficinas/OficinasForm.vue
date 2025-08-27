<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-3">
            <FormInput v-model="form.codigo" id="codigo" label="Código" :error="errors.codigo" :autofocus="false"
                :required="false" :disabled="form.id == null" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.siglas" id="siglas" label="Siglas" :error="errors.siglas" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-6">
            <FormInput v-model="form.nombre" id="nombre" label="Nombre" :error="errors.nombre" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-4">
            <FormInput type="number" v-model="form.ordinal" id="ordinal" label="Ordinal" :error="errors.ordinal"
                :autofocus="false" :required="false" />
        </div>
        <div class="col-span-12">
            <label for="tenant_id" class="block font-bold mb-3">Tenant</label>
            <SelectTenant ref="elSelectTenant" @selectedItem="onTenantSelected" required="true"
                :invalid="errors.tenant_id != null" />
            <small v-if="errors.tenant_id" class="text-red-500">{{ errors.tenant_id }}</small>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import OficinasService from '@/services/OficinasService.js';
    import FormInput from '../../components/FormInput.vue';
    import SelectTenant from '@/components/SelectTenant.vue';

    //Emits
    const emits = defineEmits(["saved", "notsaved"]);

    const props = defineProps({
        //
    });

    //Estados reactivos
    const form = ref({
        id: null,
        codigo: null,
        siglas: null,
        nombre: null,
        ordinal: null,
        tenant_id: null,
    });
    const errors = ref({});

    const elSelectTenant = ref(null);

    //onMounted
    onMounted(() => {
        reset();
    });

    async function save() {
        try {
            await OficinasService.save(form.value);
            emits('saved');
        } catch (e) {
            console.log(e.response.data.errors)
            setErrors(e.response.data.errors);
            emits('notsaved');
        }
    };

    function setValue(row) {
        form.value = row;
        elSelectTenant.value.updateProp(row.tenant);
    };

    function setErrors(row) {
        errors.value.codigo = row.codigo ? row.codigo[0] : null;
        errors.value.siglas = row.siglas ? row.siglas[0] : null;
        errors.value.nombre = row.nombre ? row.nombre[0] : null;
        errors.value.ordinal = row.ordinal ? row.ordinal[0] : null;
        errors.value.tenant_id = row.tenant_id ? row.tenant_id[0] : null;
    };

    function reset() {
        form.value = {};
        errors.value = {};
    };

    function onTenantSelected(item) {
        form.value.tenant_id = item.id;
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
