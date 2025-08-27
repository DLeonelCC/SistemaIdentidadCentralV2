<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-4">
            <AutoComplete v-model="modelDepartamento" optionLabel="nombre" :suggestions="optionsDepartamentos"
                @complete="filterDepartamentos" @item-select="updateDepartamento($event.value)"
                placeholder="Seleccione Departamento" :disabled="disabled" class="w-full" forceSelection dropdown />
        </div>
        <div class="col-span-4">
            <AutoComplete v-model="modelProvincia" optionLabel="nombre" :suggestions="optionsProvincias"
                @complete="filterProvincias" @item-select="updateProvincia($event.value)"
                placeholder="Seleccione Provincia" :disabled="disabled" class="w-full" forceSelection dropdown />
        </div>
        <div class="col-span-4">
            <AutoComplete v-model="modelDistrito" optionLabel="nombre" :suggestions="optionsDistritos"
                @complete="filterDistritos" @item-select="updateDistrito($event.value)"
                placeholder="Seleccione Distrito" :disabled="disabled" class="w-full" forceSelection dropdown />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import UbigeosService from '@/services/UbigeosService';
import AutoComplete from 'primevue/autocomplete';

//Emits
const emits = defineEmits(["selectedItem"]);

//Props
const props = defineProps({
    ubigeo_cod: { type: String, default: null },
    disabled: { type: Boolean, default: false },
    obra: { default: null },
});

//Estados reactivos
const stringDepartamentos = ref([]);
const optionsDepartamentos = ref([]);
const modelDepartamento = ref({
    "id": 2090,
    "codigo": 210000,
    "tipo": "departamento",
    "cod_dep": "21",
    "cod_prov": "00",
    "cod_dist": "00",
    "nombre": "Puno",
});

const stringProvincias = ref([]);
const optionsProvincias = ref([]);
const modelProvincia = ref(null);

const stringDistritos = ref([]);
const optionsDistritos = ref([]);
const modelDistrito = ref(null);

//OnMounted
onMounted(async () => {
    if (props.ubigeo_cod != null) {
        await getUbigeo(props.ubigeo_cod);
    }
    if (props.obra != null) {
        setUbigeo();
    } else {
        await getDepartamentos();
    }
});

//Métodos
function setUbigeo() {
    modelDepartamento.value = props.obra.departamento;
    modelProvincia.value = props.obra.provincia;
    modelDistrito.value = props.obra.ubigeo;
}

async function getUbigeo(ubigeo_cod) {
    await UbigeosService.getUbigeo({ params: { ubigeo_cod } }).then(
        (response) => {
            modelDepartamento.value = response.departamento;
            modelProvincia.value = response.provincia;
            modelDistrito.value = response.distrito;
        }
    );
    getProvincias(modelDepartamento.value.cod_dep);
    getDistritos(modelDepartamento.value.cod_dep, modelProvincia.value.cod_prov);
}

async function getDepartamentos() {
    modelDepartamento.value = {
        "id": 2090,
        "codigo": 210000,
        "tipo": "departamento",
        "cod_dep": "21",
        "cod_prov": "00",
        "cod_dist": "00",
        "nombre": "Puno",
    };
    modelProvincia.value = null;
    modelDistrito.value = null;
    getProvincias(modelDepartamento.value.cod_dep);
    stringDepartamentos.value = await UbigeosService.getDepartamentos();
}

async function getProvincias(cod_dep) {
    stringProvincias.value = await UbigeosService.getProvincias(cod_dep);
}

async function getDistritos(cod_dep, cod_prov) {
    stringDistritos.value = await UbigeosService.getDistritos(cod_dep, cod_prov);
}

async function filterDepartamentos(event) {
    const val = event.query;
    if (val.trim().length === 0) {
        optionsDepartamentos.value = stringDepartamentos.value;
    } else {
        const needle = val.toLowerCase();
        optionsDepartamentos.value = stringDepartamentos.value.filter(x => x.nombre.toLowerCase().includes(needle));
    }
}

async function filterProvincias(event) {
    const val = event.query;
    if (val.trim().length === 0) {
        optionsProvincias.value = stringProvincias.value;
    } else {
        const needle = val.toLowerCase();
        optionsProvincias.value = stringProvincias.value.filter(x => x.nombre.toLowerCase().includes(needle));
    }
}

async function filterDistritos(event) {
    const val = event.query;
    if (val.trim().length === 0) {
        optionsDistritos.value = stringDistritos.value;
    } else {
        const needle = val.toLowerCase();
        optionsDistritos.value = stringDistritos.value.filter(x => x.nombre.toLowerCase().includes(needle));
    }
}

function updateUbigeo() {
    if (modelDepartamento.value && modelProvincia.value && modelDistrito.value) {
        const ubigeo_cod = modelDepartamento.value.cod_dep + modelProvincia.value.cod_prov + modelDistrito.value.cod_dist;
        emits("selectedItem", ubigeo_cod);
    }
}

function updateDepartamento(value) {
    modelDepartamento.value = value;
    modelProvincia.value = null;
    modelDistrito.value = null;
    getProvincias(value.cod_dep);
}

function updateProvincia(value) {
    modelProvincia.value = value;
    modelDistrito.value = null;
    getDistritos(value.cod_dep, value.cod_prov);
}

function updateDistrito(value) {
    modelDistrito.value = value;
    updateUbigeo();
}

function reset() {
    modelProvincia.value = null;
    modelDistrito.value = null;
}

//Expose
defineExpose({
    reset,
    getUbigeo,
    setUbigeo
});
</script>