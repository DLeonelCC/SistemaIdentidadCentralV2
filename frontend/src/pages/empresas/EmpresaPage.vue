<template>
    <div class="grid">
        <div class="col-12 md:col-9 lg:col-10">
            <div class="mb-4">
                <UpdateEmpresaInformationCard/>
            </div>
        </div>
    </div>
</template>

<script setup>
    import UpdateEmpresaInformationCard from './UpdateEmpresaInformationCard.vue';
    import { ref, computed, onMounted } from 'vue';

    const screenWidth = ref(window.innerWidth);

    onMounted(() => {
        window.addEventListener('resize', () => {
            screenWidth.value = window.innerWidth;
        });
    });
</script>