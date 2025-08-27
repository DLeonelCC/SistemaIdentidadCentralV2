<template>
    <template v-if="loading">
        <ProgressBar mode="indeterminate" style="height: 6px" class="bg-blue-200" />
    </template>

    <div class="grid grid-cols-12 gap-x-4 gap-y-4">
        <div class="col-span-3">
            <label for="tipo_doc" class="block font-bold mb-3">Tipo Doc.</label>
            <SelectTipoDoc id="tipo_doc" v-model="form.tipo_doc" />
            <small v-if="errors.tipo_doc" class="text-red-500">{{ errors.tipo_doc }}</small>
        </div>

        <div class="col-span-3">
            <label for="num_doc" class="block font-bold mb-3">Num. Doc.</label>
            <div class="flex gap-1">
                <InputText id="num_doc" v-model.trim="form.num_doc" required="true" autofocus
                    :invalid="errors.num_doc != null" fluid />
                <Button icon="pi pi-search" class="p-button-outlined" style="width: 50px" @click="search" />
            </div>
            <small v-if="errors.num_doc" class="text-red-500">{{ errors.num_doc }}</small>
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.nombres" id="nombres" label="Nombres" :error="errors.nombres" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.ap_paterno" id="ap_paterno" label="Ap. Paterno" :error="errors.ap_paterno"
                :autofocus="false" :required="false" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.ap_materno" id="ap_materno" label="Ap. Materno" :error="errors.ap_materno"
                :autofocus="false" :required="false" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.email" id="email" label="Email" :error="errors.email" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.direccion" id="direccion" label="Dirección" :error="errors.direccion"
                :autofocus="false" :required="false" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.celular" id="celular" label="Celular" :error="errors.celular" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-3">
            <label for="sexo" class="block font-bold mb-3">Sexo</label>
            <SelectSexo id="sexo" v-model="form.sexo" />
            <small v-if="errors.sexo" class="text-red-500">{{ errors.sexo }}</small>
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.ruc" id="ruc" label="RUC" :error="errors.ruc" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-3">
            <FormInput type="date" v-model="form.fec_nacimiento" id="fec_nacimiento" label="F. Nacimiento"
                :error="errors.fec_nacimiento" :autofocus="false" :required="false" @change="calcularEdad()" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="edad" id="edad" label="Edad" :error="errors.edad" :autofocus="false" :required="false"
                :readonly="true" variant="filled" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.nacionalidad" id="nacionalidad" label="Nacionalidad" :error="errors.nacionalidad"
                :autofocus="false" :required="false" />
        </div>
        <div class="col-span-3">
            <label for="estado_civil" class="block font-bold mb-3">Estado Civil</label>
            <SelectEstadoCivil v-model="form.estado_civil" />
            <small v-if="errors.estado_civil" class="text-red-500">{{ errors.estado_civil }}</small>
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.pais" id="pais" label="Pais" :error="errors.pais" :autofocus="false"
                :required="false" />
        </div>
        <div class="col-span-3">
            <FormInput v-model="form.num_cuenta" id="num_cuenta" label="Cuenta B.N." :error="errors.num_cuenta"
                :autofocus="false" :required="false" />
        </div>
    </div>

    <div>
        <label for="select_ubigeo_nacimiento" class="block font-bold mb-4">Lugar de Nacimiento</label>
        <div class="col-span-12">
            <SelectUbigeo id="select_ubigeo_nacimiento" ref="elSelectUbigeoNacimiento"
                @selectedItem="updateUbigeoNacimiento($event)">
            </SelectUbigeo>
        </div>
    </div>
    <div>
        <label for="select_ubigeo_actual" class="block font-bold mb-4">Lugar Actual</label>
        <div class="col-span-12">
            <SelectUbigeo id="select_ubigeo_actual" ref="elSelectUbigeoActual"
                @selectedItem="updateUbigeoActual($event)">
            </SelectUbigeo>
        </div>
    </div>

    <div v-show="showTenant" class="grid grid-cols-12 gap-x-4 gap-y-4">
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
import SelectTipoDoc from '@/components/SelectTipoDoc.vue';
import SelectSexo from '@/components/SelectSexo.vue';
import SelectEstadoCivil from '@/components/SelectEstadoCivil.vue';
import PersonasService from '@/services/PersonasService.js';
import SelectUbigeo from '@/components/SelectUbigeo.vue';
import ProgressBar from 'primevue/progressbar';
import FormInput from '@/components/FormInput.vue';
import SelectTenant from '@/components/SelectTenant.vue';

//Emits
const emits = defineEmits(["saved", "notsaved", 'search']);

const props = defineProps({
    showTenant: { type: Boolean, default: false },
});

//Estados reactivos
const form = ref({
    id: null,
    tipo_doc: null,
    num_doc: null,
    nombre_completo: null,
    nombres: null,
    ap_paterno: null,
    ap_materno: null,
    email: null,
    direccion: null,
    celular: null,
    fec_nacimiento: null,
    sexo: null,
    num_cuenta: null,
    nacionalidad: null,
    estado_civil: null,
    ruc: null,
    pais: null,
    datos_completos: null,
    ubigeo_actual: null,
    ubigeo_nacimiento: null,
    tenant_id: null,
});
const edad = ref(null);
const errors = ref({});

const loading = ref(false);

const elSelectUbigeoNacimiento = ref(null);
const elSelectUbigeoActual = ref(null);
const elSelectTenant = ref(null);

//onMounted
onMounted(() => {
    reset();
});

async function save() {
    try {
        form.value.nombre_completo = form.value.ap_paterno + ' ' + form.value.ap_materno + ' ' + form.value.nombres;
        await PersonasService.save(form.value);
        emits('saved');
    } catch (e) {
        console.log(e.response.data.errors)
        setErrors(e.response.data.errors);
        emits('notsaved');
    }
};

function setValue(row) {
    form.value = row;
    if (form.value.ubigeo_nacimiento) {
        elSelectUbigeoNacimiento.value.getUbigeo(row.ubigeo_nacimiento);
    }
    if (form.value.ubigeo_actual) {
        elSelectUbigeoActual.value.getUbigeo(row.ubigeo_actual);
    }
    if (form.value.tenant) {
        elSelectTenant.value.updateProp(row.tenant);
    }
    if (form.value.fec_nacimiento != null) {
        calcularEdad()
    }
};

function setErrors(row) {
    errors.value.tipo_doc = row.tipo_doc ? row.tipo_doc[0] : null;
    errors.value.num_doc = row.num_doc ? row.num_doc[0] : null;
    errors.value.nombre_completo = row.nombre_completo ? row.nombre_completo[0] : null;
    errors.value.nombres = row.nombres ? row.nombres[0] : null;
    errors.value.ap_paterno = row.ap_paterno ? row.ap_paterno[0] : null;
    errors.value.ap_materno = row.ap_materno ? row.ap_materno[0] : null;
    errors.value.email = row.email ? row.email[0] : null;
    errors.value.direccion = row.direccion ? row.direccion[0] : null;
    errors.value.celular = row.celular ? row.celular[0] : null;
    errors.value.fec_nacimiento = row.fec_nacimiento ? row.fec_nacimiento[0] : null;
    errors.value.sexo = row.sexo ? row.sexo[0] : null;
    errors.value.num_cuenta = row.num_cuenta ? row.num_cuenta[0] : null;
    errors.value.nacionalidad = row.nacionalidad ? row.nacionalidad[0] : null;
    errors.value.estado_civil = row.estado_civil ? row.estado_civil[0] : null;
    errors.value.ruc = row.ruc ? row.ruc[0] : null;
    errors.value.pais = row.pais ? row.pais[0] : null;
    errors.value.datos_completos = row.datos_completos ? row.datos_completos[0] : null;
    errors.value.ubigeo_actual = row.ubigeo_actual ? row.ubigeo_actual[0] : null;
    errors.value.ubigeo_nacimiento = row.ubigeo_nacimiento ? row.ubigeo_nacimiento[0] : null;
    errors.value.tenant_id = row.tenant_id ? row.tenant_id[0] : null;
};

function reset() {
    form.value = {};
    form.value.tipo_doc = 'DNI';
    form.value.pais = 'PERÚ'
    form.value.nacionalidad = 'PERUANO';
    errors.value = {};
};

async function search() {
    loading.value = true;
    const persona = await PersonasService.getByDni(form.value.num_doc);
    if (persona.id) {
        form.value = persona;
        if (persona.ubigeo_actual) { elSelectUbigeoActual.value.getUbigeo(persona.ubigeo_actual) };
        if (persona.ubigeo_nacimiento) { elSelectUbigeoNacimiento.value.getUbigeo(persona.ubigeo_nacimiento) };
        if (persona.tenant) { elSelectTenant.value.updateProp(persona.tenant) };
        calcularEdad();
        emits("search", form.value.num_doc); // Continua la busqueda - datos laborales
    } else {
        searchByApiDni();
    }
    loading.value = false;
}

const searchByApiDni = async () => {
    if (form.value.num_doc.length == 8) {
        try {
            if (form.value.tipo_doc == 'DNI') {
                await PersonasService.searchByApiDni(form.value.num_doc).then(res => {
                    if (res.fuente == 'RENIEC-PIDE') {
                        form.value.ap_paterno = res.data.apPrimer;
                        form.value.ap_materno = res.data.apSegundo;
                        form.value.nombres = res.data.prenombres;
                        form.value.estado_civil = res.data.estadoCivil;
                    } else if (res.fuente == 'APIS.NET.PE') {
                        form.value.ap_paterno = res.data.apellidoPaterno;
                        form.value.ap_materno = res.data.apellidoMaterno;
                        form.value.nombres = res.data.nombres;
                    }
                });
            }
        } catch (error) {
            console.error(error);
        }
    }
};

function calcularEdad() {
    let fechaActual = new Date();
    let fechaNacimiento = new Date(form.value.fec_nacimiento);
    edad.value = parseInt(fechaActual.getFullYear(), 10) - parseInt(fechaNacimiento.getFullYear(), 10);
    if (parseInt(fechaNacimiento.getMonth(), 10) >= parseInt(fechaActual.getMonth(), 10)) {
        if (parseInt(fechaNacimiento.getMonth(), 10) == parseInt(fechaActual.getMonth(), 10)) {
            if (parseInt(fechaNacimiento.getDate(), 10) >= parseInt(fechaActual.getDate(), 10)) {
                edad.value = edad.value - 1;
            }
        } else {
            edad.value = edad.value - 1;
        }
    }
}

function updateUbigeoNacimiento(event) {
    form.value.ubigeo_nacimiento = event;
}

function updateUbigeoActual(event) {
    form.value.ubigeo_actual = event;
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
