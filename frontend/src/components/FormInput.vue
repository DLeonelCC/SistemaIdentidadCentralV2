<template>
    <div :class="columnClass">
        <!-- <FloatLabel> -->
        <label :for="id" class="block font-bold mb-3">{{ label }}</label>
        <InputText :id="id" :type="type" v-model="modelValueProxy" :autofocus="autofocus" :required="required"
            :invalid="error != null" :disabled="disabled" :readonly="readonly" :variant="variant" class="w-full" />
        <!-- </FloatLabel> -->

        <small v-if="error" class="text-red-500">{{ error }}</small>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import InputText from 'primevue/inputtext';
    // import FloatLabel from 'primevue/floatlabel';

    const props = defineProps({
        modelValue: [String, Number, Date],
        label: String,
        id: String,
        type: {
            type: String,
            default: 'text'
        },
        error: String,
        autofocus: {
            type: Boolean,
            default: false
        },
        required: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        readonly: {
            type: Boolean,
            default: false
        },
        variant: {
            type: String,
            default: 'outlined' // puede ser 'outlined' | 'filled'
        },
        columnClass: {
            type: String,
            default: 'col-span-3'
        }
    });

    const emit = defineEmits(['update:modelValue']);

    const modelValueProxy = computed({
        get: () => props.modelValue,
        set: (val) => emit('update:modelValue', val)
    });
</script>