<template>
    <div class="row">
        <div class="col-12" v-for="video in conteudo" :key="video.id" role="button"
            @click="$router.push({ path: 'video', query: { id: video.id, newid: video.newid } }), emitirValor({ newid: video.newid, id: video.id }), defineElemento(video.id)">
            <div class="card mb-2">
                <div class="row g-0">
                    <div class="col-md-4 align-self-center">
                        <img :src="'https://tbr-legacy-thumbnails.s3.sa-east-1.amazonaws.com/' + video.newid + '.jpg'"
                            class="img-fluid rounded" alt="miniatura do vídeo" />
                    </div>
                    <div class="col-md-8 align-self-center">
                        <p class="card-content">{{ video.titulo.length > 40 ? video.titulo.substring(0, 40) + "..." :
                            video.titulo.substring(0, 40) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
    conteudo: {
        type: Array,
        required: true
    }
});

const emit = defineEmits([
    'urlatualizada'
]);

function emitirValor(dados) {
    emit('urlatualizada', dados);
}

function defineElemento(value){
    console.log(value)
}
</script>

<style scoped>
.card-content {
    margin-bottom: 0;
    font-size: 0.9rem;
    padding: 0 4px 0 4px;
}
</style>