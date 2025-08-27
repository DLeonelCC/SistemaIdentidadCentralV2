<script setup>
    import UpdateProfileInformationCard from './UpdateProfileInformationCard.vue';
    import UpdatePasswordCard from './UpdatePasswordCard.vue';
</script>

<template>
    <div class="grid justify-content-between">
        <!-- Columna izquierda -->
        <div class="col-12 md:col-3 lg:col-2 border-1 surface-border p-3">
            <h1 class="text-3xl font-semibold mb-2">Perfil</h1>
            <span class="text-color-secondary">
                Actualiza tu foto de perfil, tu información del perfil y tu contraseña.
            </span>
        </div>

        <!-- Columna derecha -->
        <div class="col-12 md:col-9 lg:col-10">
            <div class="p-2 md:p-3">
                <div class="mb-3">
                    <UpdateProfileInformationCard />
                </div>
                <div class="mb-3">
                    <UpdatePasswordCard />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .text-color-secondary {
        color: var(--text-color-secondary);
    }
</style>
