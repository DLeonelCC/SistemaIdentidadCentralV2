<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-3">
            <label for="codigo" class="block font-bold mb-3">Código</label>
            <InputText id="codigo" v-model.trim="form.codigo" required="true" autofocus
                :invalid="errors.codigo != null" fluid :disabled="form.id == null" />
            <small v-if="errors.codigo" class="text-red-500">{{ errors.codigo }}</small>
        </div>
        <div class="col-span-9">
            <label for="nombre" class="block font-bold mb-3">Nombre</label>
            <InputText id="nombre" v-model.trim="form.nombre" required="true" autofocus :invalid="errors.nombre != null"
                fluid />
            <small v-if="errors.nombre" class="text-red-500">{{ errors.nombre }}</small>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6">
            <label for="ordinal" class="block font-bold mb-3">Ordinal</label>
            <InputText type="number" id="ordinal" v-model.trim="form.ordinal" required="true" autofocus
                :invalid="errors.ordinal != null" fluid />
            <small v-if="errors.ordinal" class="text-red-500">{{ errors.ordinal }}</small>
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
    import CargosService from '@/services/CargosService.js';
    import SelectTenant from '../../components/SelectTenant.vue';

    //Emits
    const emits = defineEmits(["saved", "notsaved"]);

    const props = defineProps({
        //
    });

    //Estados reactivos
    const form = ref({
        id: null,
        codigo: null,
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
            await CargosService.save(form.value);
            emits('saved');
        } catch (e) {
            console.log(e.response.data.errors)
            setErrors(e.response.data.errors);
            emits('notsaved');
        }
    };

    function setValue(row) {
        form.value = row;
        if (row.tenant) { elSelectTenant.value.updateProp(row.tenant) }
    };

    function setErrors(row) {
        errors.value.codigo = row.codigo ? row.codigo[0] : null;
        errors.value.nombre = row.nombre ? row.nombre[0] : null;
        errors.value.ordinal = row.ordinal ? row.ordinal[0] : null;
        errors.value.tenant_id = row.tenant_id ? row.tenant_id[0] : null;
    };

    function reset() {
        form.value = {};
        form.value.tipo = 'NORMAL';
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
