<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-4">
            <label for="tipo" class="block font-bold mb-3">Tipo Archivo</label>
            <SelectTipoArchivo id="tipo" v-model="form.tipo" />
            <small v-if="errors.tipo" class="text-red-500">{{ errors.tipo }}</small>
        </div>
        <div class="col-span-4">
            <label for="tipo_doc" class="block font-bold mb-3">Tipo Doc. Archivo</label>
            <SelectTipoDocArchivo id="tipo_doc" v-model="form.tipo_doc" />
            <small v-if="errors.tipo_doc" class="text-red-500">{{ errors.tipo_doc }}</small>
        </div>
        <div class="col-span-4">
            <label for="num_doc" class="block font-bold mb-3">Num. Doc.</label>
            <InputText id="num_doc" v-model.trim="form.num_doc" required="true" autofocus
                :invalid="errors.num_doc != null" fluid />
            <small v-if="errors.num_doc" class="text-red-500">{{ errors.num_doc }}</small>
        </div>
        <div class="col-span-4">
            <label for="fecha" class="block font-bold mb-3">Fecha</label>
            <InputText :type="'date'" type="number" id="fecha" v-model.trim="form.fecha" required="true" autofocus
                :invalid="errors.fecha != null" fluid />
            <small v-if="errors.fecha" class="text-red-500">{{ errors.fecha }}</small>
        </div>
        <div class="col-span-8">
            <label for="ordinal" class="block font-bold mb-3">Descripcion</label>
            <InputText id="descripcion" v-model.trim="form.descripcion" required="true" autofocus
                :invalid="errors.descripcion != null" fluid />
            <small v-if="errors.descripcion" class="text-red-500">{{ errors.descripcion }}</small>
        </div>
        <div class="col-span-12 sm:col-span-12 flex justify-start">
            <FileUpload ref="fileuploadRef" mode="basic" name="file" accept=".pdf, image/*, .xls, .xlsx"
                :maxFileSize="1000000" :auto="false" :multiple="false" @select="onFileSelect"
                chooseLabel="Seleccionar archivo">
            </FileUpload>
        </div>
        <div v-if="selectedFile" class="col-span-12 sm:col-span-12">
            <div class="mt-2">
                <p class="text-sm">Archivo seleccionado: <strong>{{ selectedFile.name }}</strong></p>
                <Button label="Quitar archivo" icon="pi pi-times" text @click="clearFile" />
            </div>
        </div>
        <div v-if="form.url_archivo != null" class="col-span-12">
            <a :href="storage + form.url_archivo" target="_blank"
                class="p-button p-button-sm p-button-outlined p-button-info">
                <i class="pi pi-external-link"></i>
                <span class="p-button-label">Ver archivo</span>
            </a>
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
    import ArchivosService from '@/services/ArchivosService.js';
    import SelectTenant from '../../components/SelectTenant.vue';
    import SelectTipoArchivo from '../../components/SelectTipoArchivo.vue';
    import SelectTipoDocArchivo from '../../components/SelectTipoDocArchivo.vue';

    //Emits
    const emits = defineEmits(["saved", "notsaved"]);

    const props = defineProps({
        //
    });

    //Estados reactivos
    const form = ref({
        id: null,
        tipo: null,
        tipo_doc: null,
        num_doc: null,
        fecha: null,
        descripcion: null,
        url_archivo: null,
        contrato_id: null,
        tenant_id: null,
    });
    const errors = ref({});

    const selectedFile = ref(null);
    const fileuploadRef = ref(null);

    const elSelectTenant = ref(null);

    //onMounted
    onMounted(() => {
        reset();
    });

    async function save() {
        try {
            form.value.file = selectedFile.value;
            await ArchivosService.save(form.value);
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
        errors.value.tipo = row.tipo ? row.tipo[0] : null;
        errors.value.tipo_doc = row.tipo_doc ? row.tipo_doc[0] : null;
        errors.value.num_doc = row.num_doc ? row.num_doc[0] : null;
        errors.value.fecha = row.fecha ? row.fecha[0] : null;
        errors.value.descripcion = row.descripcion ? row.descripcion[0] : null;
        errors.value.url_archivo = row.url_archivo ? row.url_archivo[0] : null;
        errors.value.contrato_id = row.contrato_id ? row.contrato_id[0] : null;
        errors.value.tenant_id = row.tenant_id ? row.tenant_id[0] : null;
    };

    function reset() {
        form.value = {};
        form.value.tipo = 'PDF';
        errors.value = {};
        selectedFile.value = null;
    };

    function onFileSelect(event) {
        selectedFile.value = event.files[0] ?? null;
    }

    function clearFile() {
        selectedFile.value = null;
        fileuploadRef.value.clear();
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
