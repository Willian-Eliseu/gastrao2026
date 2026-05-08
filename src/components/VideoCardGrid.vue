<template>
    <div class="card mb-3" role="button">
        <img :src="image" alt="" class="card-img-top">
        <div class="card-body">
            <h6 class="card-title">
                {{ title }}
            </h6>
            <p class="card-text small video-description">
                {{ description.length > 60 ? description.substring(0, 60) + "..." : description.substring(0, 60) }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
defineProps({
    image: String,
    title: String,
    description: String
});
</script>

<style scoped>
.card {
    min-height: 340px;
}

.card:hover {
    box-shadow: 5px 5px 10px #21212133;
}
</style>