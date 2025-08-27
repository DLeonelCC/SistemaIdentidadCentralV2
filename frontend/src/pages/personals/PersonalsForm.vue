<template>
    <div class="mb-3 flex gap-2">
        <Button label="Datos Personales" :severity="tab === 'DP' ? '' : 'secondary'" @click="tab = 'DP'" />
        <Button label="Datos Laborales" :severity="tab === 'DL' ? '' : 'secondary'" @click="tab = 'DL'" />
    </div>

    <div v-show="tab === 'DP'">
        <PersonasForm ref="elPersonasForm" @search="search($event)"></PersonasForm>
    </div>

    <div v-show="tab === 'DL'">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 sm:col-span-6">
                <label for="tipo_contrato" class="block font-bold mb-3">Tipo Contrato</label>
                <SelectTipoContrato v-model="form.tipo_contrato" :error-message="errors.tipo_contrato" />
                <small v-if="errors.estado_civil" class="text-red-500">{{ errors.estado_civil }}</small>
            </div>

            <div class="col-span-12 sm:col-span-3">
                <label for="categoria_ocupacional" class="block font-bold mb-3">Cat. Ocupacional</label>
                <SelectCategoriaOcupacional v-model="form.categoria_ocupacional"
                    :error-message="errors.categoria_ocupacional" />
                <small v-if="errors.categoria_ocupacional" class="text-red-500">{{ errors.categoria_ocupacional
                    }}</small>
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="form.codigo" id="codigo" label="Código" :error="errors.codigo" :autofocus="false"
                    :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="form.codigo_air" id="codigo_air" label="Código Airhsp" :error="errors.codigo_air"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput type="date" v-model="form.fec_ini" id="fec_ini" label="F. Inicio" :error="errors.fec_ini"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput type="date" v-model="form.fec_fin" id="fec_fin" label="F. Fin" :error="errors.fec_fin"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <label for="sis_pen" class="block font-bold mb-3">Sis. Pen.</label>
                <SelectSisPen v-model="form.sis_pen" :error-message="errors.sis_pen"
                    @update:model-value="limpiarPorONP($event)" />
                <small v-if="errors.sis_pen" class="text-red-500">{{ errors.sis_pen }}</small>
            </div>

            <div class="col-span-12 sm:col-span-4">
                <label for="tipo_afp" class="block font-bold mb-3">Tipo AFP</label>
                <SelectTipoAfp v-model="form.tipo_afp" :error-message="errors.tipo_afp" />
                <small v-if="errors.tipo_afp" class="text-red-500">{{ errors.tipo_afp }}</small>
            </div>

            <div class="col-span-12 sm:col-span-4">
                <FormInput v-model="form.cuspp" id="cuspp" label="CUSPP" :error="errors.cuspp" :autofocus="false"
                    :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-4">
                <label for="tip_com_seg" class="block font-bold mb-3">Tip. Com. Seg.</label>
                <SelectTipoComSeg v-model="form.tip_com_seg" :error-message="errors.tip_com_seg" />
                <small v-if="errors.tip_com_seg" class="text-red-500">{{ errors.tip_com_seg }}</small>
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="form.cond_g_oc" id="cond_g_oc" label="Cond G. OC" :error="errors.cond_g_oc"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="form.nivel_remunerativo" id="nivel_remunerativo" label="Nivel Remunerativo"
                    :error="errors.nivel_remunerativo" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="form.tiempo_entidad" id="tiempo_entidad" label="Tiempo en la Entidad"
                    :error="errors.tiempo_entidad" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="form.quinquenio" id="quinquenio" label="Quinquenio" :error="errors.quinquenio"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="form.rep_provincia" id="rep_provincia" label="R. Provincia"
                    :error="errors.rep_provincia" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-9">
                <FormInput v-model="form.observacion" id="observacion" label="Observación" :error="errors.observacion"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <label for="condicion" class="block font-bold mb-3">Condición</label>
                <SelectCondicionPersonal v-model="form.condicion" :error-message="errors.condicion" />
                <small v-if="errors.condicion" class="text-red-500">{{ errors.condicion }}</small>
            </div>

            <div class="col-span-12 sm:col-span-4 flex items-center gap-2">
                <Checkbox v-model="form.discapacidad" :binary="true" :true-value="true" :false-value="false"
                    inputId="chkDis" />
                <label for="chkDis">¿Discapacidad?</label>
            </div>

            <div class="col-span-12 sm:col-span-4 flex items-center gap-2">
                <Checkbox v-model="form.sindicalizado" :binary="true" :true-value="true" :false-value="false"
                    inputId="chkSin" />
                <label for="chkSin">¿Sindicalizado?</label>
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
    import PersonalsService from '@/services/PersonalsService.js';
    import PersonasForm from '../personas/PersonasForm.vue';
    import SelectTipoContrato from '../../components/SelectTipoContrato.vue';
    import SelectCategoriaOcupacional from '../../components/SelectCategoriaOcupacional.vue';
    import SelectSisPen from '../../components/SelectSisPen.vue';
    import SelectTipoAfp from '../../components/SelectTipoAfp.vue';
    import SelectTipoComSeg from '../../components/SelectTipoComSeg.vue';
    import SelectCondicionPersonal from '../../components/SelectCondicionPersonal.vue';
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
        codigo_air: null,
        fec_ini: null,
        fec_fin: null,
        tipo_contrato: null,
        cond_g_oc: null,
        nivel_remunerativo: null,
        tiempo_entidad: null,
        quinquenio: null,
        categoria_ocupacional: null,
        rep_provincia: null,
        sis_pen: null,
        tipo_afp: null,
        cuspp: null,
        tip_com_seg: null,
        discapacidad: null,
        sindicalizado: null,
        observacion: null,
        condicion: null,
        persona_num_doc: null,
        tenant_id: null,
    });
    const errors = ref({});
    const tab = ref('DP');

    const elPersonasForm = ref(null);
    const elSelectTenant = ref(null);

    //onMounted
    onMounted(() => {
        reset();
    });

    async function save() {
        try {
            const formPersona = getPersonasForm();

            //Cargando Persona
            form.value.id_persona = formPersona.id;
            form.value.tipo_doc = formPersona.tipo_doc;
            form.value.num_doc = formPersona.num_doc;
            form.value.nombre_completo = formPersona.ap_paterno + ' ' + formPersona.ap_materno + ' ' + formPersona.nombres;
            form.value.nombres = formPersona.nombres;
            form.value.ap_paterno = formPersona.ap_paterno;
            form.value.ap_materno = formPersona.ap_materno;
            form.value.email = formPersona.email;
            form.value.direccion = formPersona.direccion;
            form.value.celular = formPersona.celular;
            form.value.fec_nacimiento = formPersona.fec_nacimiento;
            form.value.sexo = formPersona.sexo;
            form.value.num_cuenta = formPersona.num_cuenta;
            form.value.nacionalidad = formPersona.nacionalidad;
            form.value.estado_civil = formPersona.estado_civil;
            form.value.ruc = formPersona.ruc;
            form.value.pais = formPersona.pais;
            form.value.ubigeo_actual = formPersona.ubigeo_actual;
            form.value.ubigeo_nacimiento = formPersona.ubigeo_nacimiento;

            await PersonalsService.save(form.value);
            emits('saved');
        } catch (e) {
            console.log(e.response.data.errors)
            setErrors(e.response.data.errors);
            emits('notsaved');
        }
    };

    function setValue(row) {
        tab.value = 'DL';
        form.value = row;
        elPersonasForm.value.setValue(row.persona);
        elSelectTenant.value.updateProp(row.tenant);
    };

    function setErrors(row) {
        errors.value.codigo = row.codigo ? row.codigo[0] : null;
        errors.value.codigo_air = row.codigo_air ? row.codigo_air[0] : null;
        errors.value.fec_ini = row.fec_ini ? row.fec_ini[0] : null;
        errors.value.fec_fin = row.fec_fin ? row.fec_fin[0] : null;
        errors.value.tipo_contrato = row.tipo_contrato ? row.tipo_contrato[0] : null;
        errors.value.cond_g_oc = row.cond_g_oc ? row.cond_g_oc[0] : null;
        errors.value.nivel_remunerativo = row.nivel_remunerativo ? row.nivel_remunerativo[0] : null;
        errors.value.tiempo_entidad = row.tiempo_entidad ? row.tiempo_entidad[0] : null;
        errors.value.quinquenio = row.quinquenio ? row.quinquenio[0] : null;
        errors.value.categoria_ocupacional = row.categoria_ocupacional ? row.categoria_ocupacional[0] : null;
        errors.value.rep_provincia = row.rep_provincia ? row.rep_provincia[0] : null;
        errors.value.sis_pen = row.sis_pen ? row.sis_pen[0] : null;
        errors.value.tipo_afp = row.tipo_afp ? row.tipo_afp[0] : null;
        errors.value.cuspp = row.cuspp ? row.cuspp[0] : null;
        errors.value.discapacidad = row.discapacidad ? row.discapacidad[0] : null;
        errors.value.sindicalizado = row.sindicalizado ? row.sindicalizado[0] : null;
        errors.value.tip_com_seg = row.tip_com_seg ? row.tip_com_seg[0] : null;
        errors.value.observacion = row.observacion ? row.observacion[0] : null;
        errors.value.condicion = row.condicion ? row.condicion[0] : null;
        errors.value.persona_num_doc = row.persona_num_doc ? row.persona_num_doc[0] : null;
        errors.value.tenant_id = row.tenant_id ? row.tenant_id[0] : null;
        elPersonasForm.value.setErrors(row);
    };

    function reset() {
        form.value = {};
        form.value.discapacidad = false;
        form.value.sindicalizado = false;
        form.value.condicion = 'PENDIENTE';
        errors.value = {};
    };

    function getPersonasForm() {
        return elPersonasForm.value.form;
    }

    function limpiarPorONP(event) {
        if (event != 'AFP') {
            form.value.tipo_afp = null;
            form.value.cuspp = null;
            form.value.tip_com_seg = null;
        }
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
