<template>
    <div class="card mb-3" role="button">
        <div class="row g-0">
            <div class="col-md-4">
                <img :src="image" alt="miniatura" class="img-fluid rounded-start" />
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ title }}</h5>
                    <p class="card-text video-description">
                        {{ description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue'
defineProps({
    image: String,
    title: String,
    description: String,
});
</script>

<style scoped>
.card:hover {
    box-shadow: 5px 5px 10px #21212133;
}
</style>