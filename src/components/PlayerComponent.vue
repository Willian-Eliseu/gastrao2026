<template>
    <iframe :src="video" class="video" allowfullscreen></iframe>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps({
    video: String
});
console.log(props);
</script>

<style scoped>
.video {
    width: 100%;
    aspect-ratio: 16/9;
    border: 1px solid #2929295e;
    border-radius: 8px;
}
</style>