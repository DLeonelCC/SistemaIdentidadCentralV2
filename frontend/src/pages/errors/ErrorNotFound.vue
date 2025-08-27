<template>
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-10 rounded-xl shadow-xl max-w-lg w-full text-center">
            <h1 class="text-6xl font-extrabold ">
                404
            </h1>
            <p class="text-lg text-gray-600 mt-4">
                Oops! La página que estás buscando no está disponible o ha sido movida.
            </p>

            <div class="mt-6 space-x-4">
                <Button label="Ir al Inicio" @click="goHome"
                    class="p-button p-button-raised p-button-primary text-white" />
                <Button label="Volver atrás" @click="goBack"
                    class="p-button p-button-outlined p-button-secondary text-gray-700" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import Button from 'primevue/button';

const router = useRouter();

const goHome = () => {
    router.push({ name: 'login' });
};

const goBack = () => {
    router.back();
};
</script>