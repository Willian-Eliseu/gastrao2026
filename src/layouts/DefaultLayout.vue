<script setup>
import Navbar from '@/components/NavbarComponent.vue'
import Footer from '@/components/FooterComponent.vue'
</script>

<template>
    <Navbar />
    <div id="main_content">
        <slot />
    </div>
    <Footer id="site_footer" />
</template>

<style scoped>
#main_content {
    display: flex;
    flex-direction: column;
    min-height: calc(100dvh - (241px + 76px));
}

#site_footer {
    margin-top: auto;
}
</style>