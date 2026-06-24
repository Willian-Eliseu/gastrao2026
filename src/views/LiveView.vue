<template>
    <Layout>
        <main class="bg-default align-content-center" style="min-height: calc(100dvh - (67px + 56px));">
            <!-- cabeçalho -->
            <section class="container">
                <div class="row">
                    <div class="col">
                        <h3 class="text-center mb-3 text-principal fw-semibold" v-reveal="'bottom'">35th Therapeutic Digestive Endoscopy Course</h3>
                    </div>
                </div>
            </section>

            <!-- live ativa: player e chat -->
            <section id="live" v-if="isLive">
                <div class="container" v-reveal="'bottom'">
                    <div class="row">
                        <div class="col-xl-9 mb-3">
                            <div class="ratio ratio-16x9">
                                <iframe ref="playerFrame" class="rounded" id="player-frame" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <iframe ref="chatFrame" class="rounded bg-white p-3 w-100" id="chat-frame" src=""
                                frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ondemand message -->
            <section id="message" v-else>
                <div class="container py-5" v-reveal="'bottom'">
                    <div class="row">
                        <div class="col">
                            <p class="fs-5 text-center">
                                The event will be broadcasted on live strem from 24th to 26th of June 2026.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </Layout>
</template>

<script setup>
import Layout from '@/layouts/DefaultLayout.vue';
import { ref, onMounted, onUnmounted } from 'vue';
import { useSiteStore } from '@/stores/website';
import api from '@/services/api';

const isLive = ref(false);
const playerFrame = ref(null);
const chatFrame = ref(null);
const siteStore = useSiteStore();
const pagina = ref('');
const streamkey = ref('');
const userHash = ref('');
const caption = ref('');
const captionDelay = ref('');
const newbroadcast = ref(0);

const verifyLiveStatus = async () => {
    try {
        const response = await api.post(`/livestatus/index.php`, {
            id: siteStore.tbreadId
        });
        const data = response.data;
        isLive.value = data.live == 1 ? true : false;
        pagina.value = data.pagina;
        streamkey.value = data.streamkey;
        caption.value = data.caption;
        captionDelay.value = data.captiondelay;
        newbroadcast.value = data.newbroadcast;
    } catch (error) {
        console.error('Erro ao verificar o status da live:', error.message);
    }
}

const presenceConfirm = async () => {
    const todayLogin = new Date();
    const dthLogin = `${todayLogin.getFullYear()}/${todayLogin.getMonth() + 1}/${todayLogin.getDate()} ${todayLogin.getHours()}:${todayLogin.getMinutes()}:${todayLogin.getSeconds()}`;
    const urlAPI = 'https://acessos.tbr.com.br/oldapi.php';

    userHash.value = siteStore.userHash;

    const dataForm = {
        codigo: siteStore.tbreadId,
        evento: siteStore.eventId,
        url: document.URL,
        hash: siteStore.userHash,
        inscrito: siteStore.firstname + ' ' + siteStore.lastname,
        email: siteStore.email,
        cidade: siteStore.userCity,
        estado: siteStore.userState,
        ip: siteStore.userIp,
        login: dthLogin
    }

    try {
        const response = await fetch(urlAPI, {
            method: 'POST',
            headers: new Headers(),
            body: JSON.stringify(dataForm)
        });
        const data = await response.json();
    } catch (error) {
        console.error('Erro ao confirmar presença: ', error.message);
    }
}

const presenceClose = async () => {
    if (!navigator.sendBeacon) {
        console.warn('Beacon API não suportada neste navegador. Presença pode não ser registrada corretamente.');
        return;
    }

    const todayLogout = new Date();
    const dthLogout = `${todayLogout.getFullYear()}/${todayLogout.getMonth() + 1}/${todayLogout.getDate()} ${todayLogout.getHours()}:${todayLogout.getMinutes()}:${todayLogout.getSeconds()}`;

    const urlAPI = 'https://acessos.tbr.com.br/oldapi.php';
    const dataForm = {
        hash: userHash.value,
        logout: dthLogout
    }

    navigator.sendBeacon(urlAPI, JSON.stringify(dataForm));
}

const loadLiveContent = async () => {
    //player
    let userData = {
        userid: siteStore.userId,
        name: siteStore.firstname + ' ' + siteStore.lastname,
        email: siteStore.email,
        city: siteStore.userCity,
        state: siteStore.userState,
        ip: siteStore.userIp
    };
    userData = JSON.stringify(userData);
    userData = btoa(userData);

    let verto = caption.value == 's' ? `&verto=${streamkey.value}` : '';
    let delay = (captionDelay.value > 0 && caption.value == 's') ? `&drift=${captionDelay.value}` : '';
    let type = newbroadcast.value == 1 ? 'webrtc' : 'hls';
    playerFrame.value.src = `https://player.tbr.srv.br/?type=${type}&source=${streamkey.value}${verto}${delay}&userdata=${userData}`;
    console.log(playerFrame.value.src);

    //chat
    let todayDate = new Date();
    let cY = todayDate.getFullYear();
    let cM = todayDate.getMonth() + 1;
    let cD = todayDate.getDate();

    if(cM < 10) cM = '0' + cM;
    if(cD < 10) cD = '0' + cD;

    let DateNow = `${cY}-${cM}-${cD}`;
    let chatCssUrl = '//gastrao2026.tbr.com.br/chat.css';
    let param = btoa(chatCssUrl);
    let idChat = pagina.value + '-' + DateNow;
    let userName = siteStore.firstname + ' ' + siteStore.lastname;
    chatFrame.value.src = `https://chat.tbrplay.com.br/${idChat}?user.name=${userName}&css=${param}`;    
}

const handleSize = () => {
    let playerY = document.getElementById('player-frame').offsetHeight;
    playerY = playerY < 400 ? 400 : playerY;
    document.getElementById('chat-frame').style.height = playerY + 'px';
}

onMounted(async () => {
    await verifyLiveStatus();
    await presenceConfirm();

    if (isLive.value) {
        loadLiveContent();
    }

    window.addEventListener('resize', handleSize);
    setTimeout(handleSize, 1000);
});

onUnmounted(() => {
    presenceClose();
});
</script>

<style lang="css" scoped>
#chat-frame {
    min-height: 400px;
}
</style>