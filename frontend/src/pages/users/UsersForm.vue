<template>
    <div class="mb-3 flex gap-2">
        <Button label="Datos Personales" :severity="tab === 'DP' ? '' : 'secondary'" @click="tab = 'DP'" />
        <Button label="Datos Laborales" :severity="tab === 'DL' ? '' : 'secondary'" @click="tab = 'DL'" />
        <Button label="Datos de Usuario" :severity="tab === 'DU' ? '' : 'secondary'" @click="tab = 'DU'" />
    </div>

    <div v-show="tab === 'DP'">
        <PersonasForm ref="elPersonasForm" @search="search($event)"></PersonasForm>
    </div>

    <div v-show="tab === 'DL'">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 sm:col-span-6">
                <label for="tipo_contrato" class="block font-bold mb-3">Tipo Contrato</label>
                <SelectTipoContrato v-model="formPersonal.tipo_contrato" :error-message="errors.tipo_contrato" />
                <small v-if="errors.estado_civil" class="text-red-500">{{ errors.estado_civil }}</small>
            </div>

            <div class="col-span-12 sm:col-span-3">
                <label for="categoria_ocupacional" class="block font-bold mb-3">Cat. Ocupacional</label>
                <SelectCategoriaOcupacional v-model="formPersonal.categoria_ocupacional"
                    :error-message="errors.categoria_ocupacional" />
                <small v-if="errors.categoria_ocupacional" class="text-red-500">{{ errors.categoria_ocupacional
                }}</small>
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="formPersonal.codigo" id="codigo" label="Código" :error="errors.codigo"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="formPersonal.codigo_air" id="codigo_air" label="Código Airhsp"
                    :error="errors.codigo_air" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput type="date" v-model="formPersonal.fec_ini" id="fec_ini" label="F. Inicio"
                    :error="errors.fec_ini" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput type="date" v-model="formPersonal.fec_fin" id="fec_fin" label="F. Fin"
                    :error="errors.fec_fin" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <label for="sis_pen" class="block font-bold mb-3">Sis. Pen.</label>
                <SelectSisPen v-model="formPersonal.sis_pen" :error-message="errors.sis_pen"
                    @update:model-value="limpiarPorONP($event)" />
                <small v-if="errors.sis_pen" class="text-red-500">{{ errors.sis_pen }}</small>
            </div>

            <div class="col-span-12 sm:col-span-4">
                <label for="tipo_afp" class="block font-bold mb-3">Tipo AFP</label>
                <SelectTipoAfp v-model="formPersonal.tipo_afp" :error-message="errors.tipo_afp" />
                <small v-if="errors.tipo_afp" class="text-red-500">{{ errors.tipo_afp }}</small>
            </div>

            <div class="col-span-12 sm:col-span-4">
                <FormInput v-model="formPersonal.cuspp" id="cuspp" label="CUSPP" :error="errors.cuspp"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-4">
                <label for="tip_com_seg" class="block font-bold mb-3">Tip. Com. Seg.</label>
                <SelectTipoComSeg v-model="formPersonal.tip_com_seg" :error-message="errors.tip_com_seg" />
                <small v-if="errors.tip_com_seg" class="text-red-500">{{ errors.tip_com_seg }}</small>
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="formPersonal.cond_g_oc" id="cond_g_oc" label="Cond G. OC" :error="errors.cond_g_oc"
                    :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="formPersonal.nivel_remunerativo" id="nivel_remunerativo" label="Nivel Remunerativo"
                    :error="errors.nivel_remunerativo" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="formPersonal.tiempo_entidad" id="tiempo_entidad" label="Tiempo en la Entidad"
                    :error="errors.tiempo_entidad" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="formPersonal.quinquenio" id="quinquenio" label="Quinquenio"
                    :error="errors.quinquenio" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <FormInput v-model="formPersonal.rep_provincia" id="rep_provincia" label="R. Provincia"
                    :error="errors.rep_provincia" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-9">
                <FormInput v-model="formPersonal.observacion" id="observacion" label="Observación"
                    :error="errors.observacion" :autofocus="false" :required="false" />
            </div>

            <div class="col-span-12 sm:col-span-3">
                <label for="condicion" class="block font-bold mb-3">Condición</label>
                <SelectCondicionPersonal v-model="formPersonal.condicion" :error-message="errors.condicion" />
                <small v-if="errors.condicion" class="text-red-500">{{ errors.condicion }}</small>
            </div>

            <div class="col-span-12 sm:col-span-4 flex items-center gap-2">
                <Checkbox v-model="formPersonal.discapacidad" :binary="true" :true-value="1" :false-value="0"
                    inputId="chkDis" />
                <label for="chkDis">¿Discapacidad?</label>
            </div>

            <div class="col-span-12 sm:col-span-4 flex items-center gap-2">
                <Checkbox v-model="formPersonal.sindicalizado" :binary="true" :true-value="1" :false-value="0"
                    inputId="chkSin" />
                <label for="chkSin">¿Sindicalizado?</label>
            </div>
        </div>
    </div>

    <div v-show="tab === 'DU'">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-6">
                <label for="tipo" class="block font-bold mb-3">Tipo</label>
                <SelectTipoUser v-model="form.tipo" />
                <small v-if="errors.tipo" class="text-red-500">{{ errors.tipo }}</small>
            </div>
            <div class="col-span-6">
                <label for="name" class="block font-bold mb-3">Nombre</label>
                <InputText id="name" v-model.trim="form.name" required="true" autofocus :invalid="errors.name != null"
                    fluid />
                <small v-if="errors.name" class="text-red-500">{{ errors.name }}</small>
            </div>
            <div class="col-span-6">
                <label for="email_user" class="block font-bold mb-3">Email</label>
                <InputText id="email_user" v-model.trim="form.email" required="true" :invalid="errors.email != null"
                    fluid />
                <small v-if="errors.email_user" class="text-red-500">{{ errors.email_user }}</small>
            </div>
            <div class="col-span-6">
                <label for="password" class="block font-bold mb-3">Contraseña</label>
                <InputGroup>
                    <InputText id="password" :type="isPassword ? 'password' : 'text'" v-model.trim="form.password"
                        required="true" :invalid="errors.password != null" fluid />
                    <Button :icon="isPassword ? 'pi pi-eye-slash' : 'pi pi-eye'" severity="secondary"
                        @click="isPassword = !isPassword" />
                </InputGroup>
                <small v-if="errors.password" class="text-red-500">{{ errors.password }}</small>
            </div>
            <div class="col-span-12">
                <label for="cargo_id" class="block font-bold mb-3">Cargo</label>
                <SelectCargo ref="elSelectCargo" :tipo="'NORMAL'" @selectedItem="onCargoSelected" required="true"
                    :invalid="errors.cargo_id != null" />
                <small v-if="errors.cargo_id" class="text-red-500">{{ errors.cargo_id }}</small>
            </div>
            <div class="col-span-12">
                <label for="oficina_id" class="block font-bold mb-3">Oficina</label>
                <SelectOficina ref="elSelectOficina" @selectedItem="onOficinaSelected" required="true"
                    :invalid="errors.oficina_id != null" />
                <small v-if="errors.oficina_id" class="text-red-500">{{ errors.oficina_id }}</small>
            </div>
            <div class="col-span-6">
                <label for="estado" class="block font-bold mb-3">Activo</label>
                <SelectEstadoUser v-model="form.estado" />
                <small v-if="errors.estado" class="text-red-500">{{ errors.estado }}</small>
            </div>
            <div class="col-span-6">
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
    import UsersService from '@/services/UsersService.js';
    import SelectTipoUser from '@/components/SelectTipoUser.vue';
    import SelectEstadoUser from '@/components/SelectEstadoUser.vue';
    import PersonasForm from '@/pages/personas/PersonasForm.vue';
    import SelectTipoContrato from '../../components/SelectTipoContrato.vue';
    import SelectCategoriaOcupacional from '../../components/SelectCategoriaOcupacional.vue';
    import SelectSisPen from '../../components/SelectSisPen.vue';
    import SelectTipoAfp from '../../components/SelectTipoAfp.vue';
    import SelectTipoComSeg from '../../components/SelectTipoComSeg.vue';
    import SelectCondicionPersonal from '../../components/SelectCondicionPersonal.vue';
    import FormInput from '../../components/FormInput.vue';
    import SelectOficina from '@/components/SelectOficina.vue';
    import SelectCargo from '@/components/SelectCargo.vue';
    import SelectTenant from '@/components/SelectTenant.vue';
    import PersonalsService from '@/services/PersonalsService.js';

    //Emits
    const emits = defineEmits(["saved", "notsaved"]);

    const props = defineProps({
        //
    });

    //Estados reactivos
    const form = ref({
        id: null,
        tipo: null,
        name: null,
        email: null,
        password: null,
        estado: null,
        cargo_id: null,
        oficina_id: null,
        tenant_id: null,
    });
    const isPassword = ref(true);

    const formPersonal = ref({
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
    });

    const elPersonasForm = ref(null);

    const errors = ref({});
    const tab = ref('DP');

    const elSelectCargo = ref(null);
    const elSelectOficina = ref(null);
    const elSelectTenant = ref(null);

    //onMounted
    onMounted(() => {
        reset();
    });

    async function save() {
        try {
            const formPersona = getPersonasForm();
            const formSend = {};

            //Cargando Persona
            formSend.id_persona = formPersona.id;
            formSend.tipo_doc = formPersona.tipo_doc;
            formSend.num_doc = formPersona.num_doc;
            formSend.nombre_completo = formPersona.ap_paterno + ' ' + formPersona.ap_materno + ' ' + formPersona.nombres;
            formSend.nombres = formPersona.nombres;
            formSend.ap_paterno = formPersona.ap_paterno;
            formSend.ap_materno = formPersona.ap_materno;
            formSend.email = formPersona.email;
            formSend.direccion = formPersona.direccion;
            formSend.celular = formPersona.celular;
            formSend.fec_nacimiento = formPersona.fec_nacimiento;
            formSend.sexo = formPersona.sexo;
            formSend.num_cuenta = formPersona.num_cuenta;
            formSend.nacionalidad = formPersona.nacionalidad;
            formSend.estado_civil = formPersona.estado_civil;
            formSend.ruc = formPersona.ruc;
            formSend.pais = formPersona.pais;
            formSend.ubigeo_actual = formPersona.ubigeo_actual;
            formSend.ubigeo_nacimiento = formPersona.ubigeo_nacimiento;

            //Cargando Personal
            formSend.id_personal = formPersonal.value.id;
            formSend.codigo = formPersonal.value.codigo;
            formSend.codigo_air = formPersonal.value.codigo_air;
            formSend.fec_ini = formPersonal.value.fec_ini;
            formSend.fec_fin = formPersonal.value.fec_fin;
            formSend.tipo_contrato = formPersonal.value.tipo_contrato;
            formSend.cond_g_oc = formPersonal.value.cond_g_oc;
            formSend.nivel_remunerativo = formPersonal.value.nivel_remunerativo;
            formSend.tiempo_entidad = formPersonal.value.tiempo_entidad;
            formSend.quinquenio = formPersonal.value.quinquenio;
            formSend.categoria_ocupacional = formPersonal.value.categoria_ocupacional;
            formSend.rep_provincia = formPersonal.value.rep_provincia;
            formSend.sis_pen = formPersonal.value.sis_pen;
            formSend.tipo_afp = formPersonal.value.tipo_afp;
            formSend.cuspp = formPersonal.value.cuspp;
            formSend.tip_com_seg = formPersonal.value.tip_com_seg;
            formSend.discapacidad = formPersonal.value.discapacidad;
            formSend.sindicalizado = formPersonal.value.sindicalizado;
            formSend.observacion = formPersonal.value.observacion;
            formSend.condicion = formPersonal.value.condicion;
            formSend.persona_num_doc = formPersonal.value.persona_num_doc;

            //Cargando User
            formSend.id_user = form.value.id;
            formSend.tipo = form.value.tipo;
            formSend.name = form.value.name;
            formSend.email_user = form.value.email;
            formSend.password = form.value.password;
            formSend.estado = form.value.estado;
            formSend.cargo_id = form.value.cargo_id;
            formSend.oficina_id = form.value.oficina_id;
            formSend.tenant_id = form.value.tenant_id;

            formSend.id = 9999999;
            await UsersService.save(formSend);
            emits('saved');
        } catch (e) {
            console.log(e.response.data.errors)
            setErrors(e.response.data.errors);
            emits('notsaved');
        }
    };

    function setValue(row) {
        tab.value = 'DU';
        form.value = row;
        if (row.cargo) { elSelectCargo.value.updateProp(row.cargo); }
        if (row.oficina) { elSelectOficina.value.updateProp(row.oficina); }
        if (row.tenant) { elSelectTenant.value.updateProp(row.tenant); }

        if (row.persona) {
            elPersonasForm.value.setValue(row.persona);
        }
        if (row.personal) {
            formPersonal.value = row.personal;
        }
    };

    function setErrors(row) {
        //User
        errors.value.tipo = row.tipo ? row.tipo[0] : null;
        errors.value.name = row.name ? row.name[0] : null;
        errors.value.email_user = row.email_user ? row.email_user[0] : null;
        errors.value.password = row.password ? row.password[0] : null;
        errors.value.estado = row.estado ? row.estado[0] : null;

        //Personal
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

        //Persona
        elPersonasForm.value.setErrors(row);
    };

    function reset() {
        form.value = {};
        form.value.estado = 'ACTIVO';
        errors.value = {};
    };

    async function search(event) {
        let res = await PersonalsService.getByDni(event);
        if (res) {
            formPersonal.value = res;
        }

        await UsersService.getByDni(event).then((res) => {
            if (res) {
                form.value = res;
                if (res.cargo) { elSelectCargo.value.updateProp(formPersonal.value.cargo) };
                if (res.oficina) { elSelectOficina.value.updateProp(formPersonal.value.oficina) };
            }
        });
    }

    function limpiarPorONP(event) {
        if (event != 'AFP') {
            formPersonal.value.tipo_afp = null;
            formPersonal.value.cuspp = null;
            formPersonal.value.tip_com_seg = null;
        }
    }

    function getPersonasForm() {
        return elPersonasForm.value.form;
    }

    function onCargoSelected(item) {
        form.value.cargo_id = item.id;
    }

    function onOficinaSelected(item) {
        form.value.oficina_id = item.id;
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