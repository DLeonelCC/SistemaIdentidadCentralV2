<template>
    <div class="mb-3 flex gap-2">
        <Button label="Datos Generales" :severity="tab === 'DG' ? '' : 'secondary'" @click="tab = 'DG'" />
        <Button label="Datos Presupuestales" :severity="tab === 'DP' ? '' : 'secondary'" @click="tab = 'DP'" />
    </div>

    <div v-show="tab === 'DG'">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 sm:col-span-4">
                <label for="tipo" class="block font-bold mb-3">Tipo</label>
                <SelectTipoProyecto v-model="form.tipo" :error-message="errors.tipo" />
                <small v-if="errors.tipo" class="text-red-500">{{ errors.tipo }}</small>
            </div>
            <div class="col-span-12 sm:col-span-8">
                <FormInput v-model="form.num_doc_resolucion" id="num_doc_resolucion" label="N° Resolución"
                    :error="errors.num_doc_resolucion" :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-6">
                <FormInput v-model="form.cui" id="cui" label="Código CUI" :error="errors.cui" :autofocus="false"
                    :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-6">
                <FormInput v-model="form.meta_codigo" id="meta_codigo" label="Meta" :error="errors.meta_codigo"
                    :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <FormInput v-model="form.codigo" id="codigo" label="Código Descripción" :error="errors.codigo"
                    :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <FormInput v-model="form.meta" id="meta" label="Meta Descripción" :error="errors.meta"
                    :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label for="nombre" class="block font-bold mb-3">Nombre</label>
                <Textarea v-model="form.nombre" rows="3" :class="{ 'p-invalid': errors.nombre }" class="w-full" />
                <small v-if="errors.nombre" class="text-red-500">{{ errors.nombre }}</small>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label for="obra" class="block font-bold mb-3">Obra</label>
                <Textarea v-model="form.obra" rows="3" :class="{ 'p-invalid': errors.obra }" class="w-full" />
                <small v-if="errors.obra" class="text-red-500">{{ errors.obra }}</small>
            </div>
            <div class="col-span-12 sm:col-span-12">
                <label for="oficina_id" class="block font-bold mb-3">Oficina</label>
                <SelectOficina ref="elSelectOficina" :error-message="errors.oficina_id"
                    @selectedItem="onOficinaSelected($event)" />
                <small v-if="errors.oficina_id" class="text-red-500">{{ errors.oficina_id }}</small>
            </div>
            <div class="col-span-12 sm:col-span-12">
                <label for="select_ubigeo_codigo" class="block font-bold mb-4">Ubicación</label>
                <div class="col-span-12">
                    <SelectUbigeo id="select_ubigeo_codigo" ref="elSelectUbigeoCodigo"
                        @selectedItem="updateUbigeoCodigo($event)">
                    </SelectUbigeo>
                </div>
            </div>
        </div>

    </div>

    <div v-show="tab === 'DP'">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 sm:col-span-12">
                <FormInput v-model="form.programa_presupuestal" id="programa_presupuestal" label="Programa Presupuestal"
                    :error="errors.programa_presupuestal" :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label for="modalidad_ejecucion" class="block font-bold mb-3">Modalidad Ejecución</label>
                <SelectModalidadEjecucionProyecto v-model="form.modalidad_ejecucion"
                    :error-message="errors.modalidad_ejecucion" />
                <small v-if="errors.modalidad_ejecucion" class="text-red-500">{{ errors.modalidad_ejecucion }}</small>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label for="fte_fto" class="block font-bold mb-3">Fte. Fto.</label>
                <SelectFteFtoProyecto v-model="form.fte_fto" :error-message="errors.fte_fto" />
                <small v-if="errors.fte_fto" class="text-red-500">{{ errors.fte_fto }}</small>
            </div>
            <div class="col-span-12 sm:col-span-4">
                <label for="escala" class="block font-bold mb-3">Tipo Escala</label>
                <SelectTipoEscala v-model="form.escala" :error-message="errors.escala" />
                <small v-if="errors.escala" class="text-red-500">{{ errors.escala }}</small>
            </div>
            <div class="col-span-12 sm:col-span-4">
                <label for="tipo_categoria" class="block font-bold mb-3">Tipo Categoria</label>
                <SelectTipoCategoriaAcceso v-model="form.tipo_categoria" :error-message="errors.tipo_categoria" />
                <small v-if="errors.tipo_categoria" class="text-red-500">{{ errors.tipo_categoria }}</small>
            </div>
            <div class="col-span-12 sm:col-span-4">
                <label for="tipo_acceso" class="block font-bold mb-3">Tipo Acceso</label>
                <SelectTipoAcceso v-model="form.tipo_acceso" :error-message="errors.tipo_acceso" />
                <small v-if="errors.tipo_acceso" class="text-red-500">{{ errors.tipo_acceso }}</small>
            </div>
            <div class="col-span-12 sm:col-span-4">
                <label for="estado" class="block font-bold mb-3">Estado</label>
                <SelectEstadoProyecto v-model="form.estado" :error-message="errors.estado" />
                <small v-if="errors.estado" class="text-red-500">{{ errors.estado }}</small>
            </div>
            <div class="col-span-12 sm:col-span-4">
                <FormInput v-model="form.charaux1" id="charaux1" label="P.P. Costo Directo" :error="errors.charaux1"
                    :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-4">
                <FormInput v-model="form.charaux2" id="charaux2" label="P.P. Estudios y Proyectos"
                    :error="errors.charaux2" :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-4">
                <FormInput v-model="form.charaux3" id="charaux3" label="P.P. Expediente Tecnico"
                    :error="errors.charaux3" :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12 sm:col-span-4">
                <FormInput v-model="form.charaux4" id="charaux4" label="P.P Gastos Generales" :error="errors.charaux4"
                    :autofocus="false" :required="false" />
            </div>
            <div class="col-span-12">
                <label for="tenant_id" class="block font-bold mb-3">Tenant</label>
                <SelectTenant ref="elSelectTenant" @selectedItem="onTenantSelected" required="true"
                    :invalid="errors.tenant_id != null" />
                <small v-if="errors.tenant_id" class="text-red-500">{{ errors.tenant_id }}</small>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ProyectosService from '@/services/ProyectosService.js';
import SelectTipoProyecto from '../../components/SelectTipoProyecto.vue';
import SelectOficina from '../../components/SelectOficina.vue';
import SelectUbigeo from '../../components/SelectUbigeo.vue';
import SelectModalidadEjecucionProyecto from '../../components/SelectModalidadEjecucionProyecto.vue';
import SelectFteFtoProyecto from '../../components/SelectFteFtoProyecto.vue';
import SelectTipoEscala from '../../components/SelectTipoEscala.vue';
import SelectTipoCategoriaAcceso from '../../components/SelectTipoCategoriaAcceso.vue';
import FormInput from '../../components/FormInput.vue';
import SelectTipoAcceso from '../../components/SelectTipoAcceso.vue';
import SelectEstadoProyecto from '../../components/SelectEstadoProyecto.vue';
import SelectTenant from '../../components/SelectTenant.vue';

//Emits
const emits = defineEmits(["saved", "notsaved"]);

const props = defineProps({
    //
});

//Estados reactivos
const form = ref({
    id: null,
    num_doc_resolucion: null,
    tipo: null,
    meta: null,
    meta_codigo: null,
    codigo: null,
    cui: null,
    nombre: null,
    obra: null,
    pliego: null,
    programa_presupuestal: null,
    modalidad_ejecucion: null,
    fte_fto: null,
    estado: null,
    cant_personal: null,
    escala: null,
    tipo_categoria: null,
    tipo_acceso: null,
    charaux1: null,
    charaux2: null,
    charaux3: null,
    charaux4: null,
    charaux5: null,
    charaux6: null,
    charaux7: null,
    charaux8: null,
    charaux9: null,
    charaux10: null,
    oficina_id: null,
    ubigeo_codigo: null,
    tenant_id: null,
});
const errors = ref({});

const elSelectOficina = ref(null);
const elSelectUbigeoCodigo = ref(null);
const elSelectTenant = ref(null);

const tab = ref('DG');

//onMounted
onMounted(() => {
    reset();
});

async function save() {
    try {
        await ProyectosService.save(form.value);
        emits('saved');
    } catch (e) {
        console.log(e.response.data.errors)
        setErrors(e.response.data.errors);
        emits('notsaved');
    }
};

function setValue(row) {
    form.value = row;
    if (form.value.oficina) {
        elSelectOficina.value.updateProp(row.oficina);
    }
    if (form.value.ubigeo_codigo) {
        elSelectUbigeoCodigo.value.getUbigeo(row.ubigeo_codigo);
    }
    if (form.value.tenant) {
        elSelectTenant.value.updateProp(row.tenant);
    }
};

function setErrors(row) {
    errors.value.num_doc_resolucion = row.num_doc_resolucion ? row.num_doc_resolucion[0] : null;
    errors.value.tipo = row.tipo ? row.tipo[0] : null;
    errors.value.meta = row.meta ? row.meta[0] : null;
    errors.value.meta_codigo = row.meta_codigo ? row.meta_codigo[0] : null;
    errors.value.codigo = row.codigo ? row.codigo[0] : null;
    errors.value.cui = row.cui ? row.cui[0] : null;
    errors.value.nombre = row.nombre ? row.nombre[0] : null;
    errors.value.obra = row.obra ? row.obra[0] : null;
    errors.value.pliego = row.pliego ? row.pliego[0] : null;
    errors.value.programa_presupuestal = row.programa_presupuestal ? row.programa_presupuestal[0] : null;
    errors.value.modalidad_ejecucion = row.modalidad_ejecucion ? row.modalidad_ejecucion[0] : null;
    errors.value.fte_fto = row.fte_fto ? row.fte_fto[0] : null;
    errors.value.tipo_acceso = row.tipo_acceso ? row.tipo_acceso[0] : null;
    errors.value.charaux1 = row.charaux1 ? row.charaux1[0] : null;
    errors.value.charaux2 = row.charaux2 ? row.charaux2[0] : null;
    errors.value.charaux3 = row.charaux3 ? row.charaux3[0] : null;
    errors.value.charaux4 = row.charaux4 ? row.charaux4[0] : null;
    errors.value.charaux5 = row.charaux5 ? row.charaux5[0] : null;
    errors.value.charaux6 = row.charaux6 ? row.charaux6[0] : null;
    errors.value.charaux7 = row.charaux7 ? row.charaux7[0] : null;
    errors.value.charaux8 = row.charaux8 ? row.charaux8[0] : null;
    errors.value.charaux9 = row.charaux9 ? row.charaux9[0] : null;
    errors.value.charaux10 = row.charaux10 ? row.charaux10[0] : null;
    errors.value.ubigeo_id = row.ubigeo_id ? row.ubigeo_id[0] : null;
    errors.value.oficina_id = row.oficina_id ? row.oficina_id[0] : null;
    errors.value.tenant_id = row.tenant_id ? row.tenant_id[0] : null;
};

function reset() {
    form.value = {};
    form.value.estado = 'PENDIENTE';
    errors.value = {};
};

function onOficinaSelected(event) {
    form.value.oficina_id = event.id;
};

function updateUbigeoCodigo(event) {
    form.value.ubigeo_codigo = event;
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
