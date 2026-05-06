<script setup>
import { RouterView } from 'vue-router';
import { onMounted } from 'vue';
import GlobalDialog from '@/components/GlobalDialog.vue';

onMounted(()=>{
  setTimeout(()=>{
    window.scrollTo(0,0);
    document.body.classList.remove('is-loading');
  }, 100);
})
</script>

<template>
  <RouterView />
  <GlobalDialog />
</template>

<style lang="css" scoped>

</style>