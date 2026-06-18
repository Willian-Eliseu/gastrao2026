<script setup>
import { onMounted, ref } from 'vue';
import Layout from '@/layouts/DefaultLayout.vue';
import { useRouter } from 'vue-router';
import api from '@/services/api';
import { useSiteStore } from '@/stores/website';

const siteStore = useSiteStore();
const router = useRouter();
const isLive = ref(false);

const verifyLiveEvent = async () => {
    try {
        const response = await api.post('/livestatus/index.php', {
            id: siteStore.tbreadId
        });

        const data = response.data;
        isLive.value = data.live == 1 ? true : false;
    } catch (error) {
        console.log('Erro ao verificar status do evento:', error.message);
    }
}

onMounted(() => {
    //verifica se o evento está ao vivo
    verifyLiveEvent();
});
</script>

<template>
    <Layout>
        <main>
            <section class="bg-default" style="min-height: calc(100dvh - 349px);">
                <div class="container py-3 py-lg-5">
                    <!-- title -->
                    <div class="row mb-4">
                        <div class="col">
                            <h1 class="text-center mb-3 text-principal fw-bold" v-reveal="'bottom'">Support</h1>
                            <h3 class="text-center mb-3 text-principal fw-semibold" v-reveal="'bottom'">35º Digestive
                                and Therapeutic Endoscopy Course</h3>
                        </div>
                    </div>

                    <div class="row g-3 justify-content-center">
                        <div class="col-md-4 d-grid" v-if="isLive">
                            <button class="btn btn-default rounded-4 p-3 shadow box-size" @click="router.push('/live')">
                                <h4 class="text-center">Watch the live stream</h4>
                                <p class="text-center">
                                    The course will be on live stream from 24th to 26th of May 2026.
                                </p>
                            </button>
                        </div>

                        <div class="col-md-4 d-grid">
                            <button class="btn btn-default rounded-4 p-3 shadow box-size" @click="router.push('/ondemand')">
                                <h4 class="text-center">Watch the recorded material</h4>
                                <p class="text-center">
                                    The recorded material will be available after the content has been edited.
                                </p>
                            </button>
                        </div>

                        <!-- <div class="col-md-4 d-grid d-none">
                            <button class="btn btn-blue p-3 shadow box-size" @click="router.push('/certificado')">
                                <h4 class="text-center">Baixe seu Certificado</h4>
                                <p class="text-center">
                                    O certificado estará disponível apenas para os usuários que assistirem o curso
                                    (presencial ou online).
                                </p>
                                <img :src="Filete" alt="" class="w-100">
                            </button>
                        </div> -->
                    </div>
                </div>
            </section>
        </main>
    </Layout>
</template>



<style lang="css" scoped>
.box-size {
    min-height: 200px;
}
</style>