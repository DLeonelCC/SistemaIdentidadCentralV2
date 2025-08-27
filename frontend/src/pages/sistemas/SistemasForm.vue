<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6">
            <FormInput v-model="form.codigo" id="codigo" label="Código" :error="errors.codigo" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-6">
            <label for="color" class="block font-bold mb-3">Color</label>
            <div class="flex items-center space-x-4">
                <InputText id="color" v-model.trim="form.color" required :invalid="errors.color != null" class="flex-1"
                    placeholder="#FFFFFF" />
                <ColorPicker v-model="form.color" inputId="cp-hex" format="hex" class="w-15 h-15 border rounded-md" />
            </div>
            <small v-if="errors.color" class="text-red-500">{{ errors.color }}</small>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <FormInput v-model="form.nombre" id="nombre" label="Nombre" :error="errors.nombre" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-12">
            <label for="descripcion" class="block font-bold mb-3">Descripción</label>
            <Textarea v-model="form.descripcion" rows="3" :class="{ 'p-invalid': errors.descripcion }" class="w-full" />
            <small v-if="errors.descripcion" class="text-red-500">{{ errors.descripcion }}</small>
        </div>
        <div class="col-span-12">
            <FormInput v-model="form.url" id="url" label="Url" :error="errors.url" :autofocus="false"
                :required="false" />
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import SistemasService from '@/services/SistemasService.js';
    import FormInput from '../../components/FormInput.vue';

    //Emits
    const emits = defineEmits(["saved", "notsaved"]);

    const props = defineProps({
        //
    });

    //Estados reactivos
    const form = ref({
        id: null,
        codigo: null,
        color: null,
        nombre: null,
        descripcion: null,
        url: null,
    });
    const errors = ref({});

    //onMounted
    onMounted(() => {
        reset();
    });

    async function save() {
        try {
            await SistemasService.save(form.value);
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
        errors.value.codigo = row.codigo ? row.codigo[0] : null;
        errors.value.color = row.color ? row.color[0] : null;
        errors.value.nombre = row.nombre ? row.nombre[0] : null;
        errors.value.descripcion = row.descripcion ? row.descripcion[0] : null;
        errors.value.url = row.url ? row.url[0] : null;
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
