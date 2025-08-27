<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <FormInput v-model="form.name" id="name" label="Nombre" :error="errors.name" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-12">
            <label for="sistema_id" class="block font-bold mb-3">Sistema</label>
            <SelectSistema ref="elSelectSistema" @selectedItem="onSistemaSelected" required="true"
                :invalid="errors.sistema_id != null" />
            <small v-if="errors.sistema_id" class="text-red-500">{{ errors.sistema_id }}</small>
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
    import RolesService from '@/services/RolesService.js';
    import FormInput from '../../components/FormInput.vue';
    import SelectSistema from '@/components/SelectSistema.vue';
    import SelectTenant from '@/components/SelectTenant.vue';

    //Emits
    const emits = defineEmits(["saved", "notsaved"]);

    const props = defineProps({
        //
    });

    //Estados reactivos
    const form = ref({
        id: null,
        name: null,
        sistema_id: null,
        tenant_id: null,
    });
    const errors = ref({});

    const elSelectSistema = ref(null);
    const elSelectTenant = ref(null);

    //onMounted
    onMounted(() => {
        reset();
    });

    async function save() {
        try {
            await RolesService.save(form.value);
            emits('saved');
        } catch (e) {
            console.log(e.response.data.errors)
            setErrors(e.response.data.errors);
            emits('notsaved');
        }
    };

    function setValue(row) {
        form.value = row;
        if (row.sistema) { elSelectSistema.value.updateProp(row.sistema) }
        if (row.tenant) { elSelectTenant.value.updateProp(row.tenant) }
    };

    function setErrors(row) {
        errors.value.name = row.name ? row.name[0] : null;
        errors.value.sistema_id = row.sistema_id ? row.sistema_id[0] : null;
        errors.value.tenant_id = row.tenant_id ? row.tenant_id[0] : null;
    };

    function reset() {
        form.value = {};
        errors.value = {};
    };

    function onSistemaSelected(item) {
        form.value.sistema_id = item.id;
    }

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
