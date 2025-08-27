<template>
  <RouterView />
</template>

<script setup>
  import { RouterView } from 'vue-router';
  import { useRouter } from 'vue-router';
  import { ref } from "vue";

  const router = useRouter();

  const items = ref([
    {
      label: 'Home',
      icon: 'pi pi-home',
      url: '/',
      action: ''
    }
  ]);

  const goLogin = () => {
    router.push('/login');
  };
</script>