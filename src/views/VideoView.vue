<script setup>
import Layout from '@/layouts/DefaultLayout.vue';
import FloatBtnComponent from '@/components/FloatBtnComponent.vue'
import { onMounted, ref } from 'vue';
import VideoListComponent from '@/components/VideoListComponent.vue';
import PlayerComponent from '@/components/PlayerComponent.vue';
import { useRoute } from 'vue-router';
import { useSiteStore } from '@/stores/website';

//adicionar a rotina para salvar o acesso no banco acesso e na tabela acesso do tbrevent

const route = useRoute();
const videoid = ref(null);
const videoNewid = ref(null);
const video = ref(null);
const title = ref('carregando');
const description = ref('carregando...');
const arrayList = ref([]);
const eventStore = useSiteStore();
const videoList = async () => {
    try {
        const apiUrl = 'https://eventos.tbr.com.br/apis/videos/?cliente=' + eventStore.tbreadId;
        const response = await fetch(apiUrl);
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status}`);
        }
        const data = await response.json();
        arrayList.value = data.dados;

        videoid.value = route.query.id;
        const videoData = data.dados.filter(item => item.id == videoid.value);
        title.value = videoData[0].titulo;
        description.value = videoData[0].sinopse;
        video.value = `https://legacy-player.tbr.com.br/${videoData[0].newid}`;
        videoNewid.value = videoData[0].newid;
    } catch (error) {
        throw new Error(`Erro na requisição: ${error}`);
    }
}

onMounted(() => {
    videoList();
});

function atualizaUrl(msg) {
    video.value = `https://legacy-player.tbr.com.br/${msg.newid}`;
    videoid.value = msg.id;
    const videoData = arrayList.value.filter(item => item.id == msg.id);
    title.value = videoData[0].titulo;
    description.value = videoData[0].sinopse;
}

</script>

<template>
    <Layout>
        <FloatBtnComponent />
        <main>
            <section class="container py-5">
                <article class="row">
                    <div class="col-lg-9">
                        <!-- player -->
                        <PlayerComponent :video="video" />
                        <div class="my-3">
                            <h4 class="fw-bold text-dark">
                                {{ title }}
                            </h4>
                            <p class="mb-3 video-description">
                                {{ description }}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 coluna">
                        <!-- button -->
                        <div class="row">
                            <div class="col-12 d-grid">
                                <button class="btn btn-primary mb-3 rounded-3 text-light"
                                    @click="$router.push({ path: 'ondemand' })">Voltar</button>
                            </div>
                        </div>
                        <!-- lista de vídeos -->
                        <VideoListComponent :conteudo="arrayList" @urlatualizada="atualizaUrl" />
                    </div>
                </article>
            </section>
        </main>
    </Layout>
</template>

<style scoped>
.coluna {
    max-height: 70vh;
    overflow-y: auto;
}
</style>