<script setup>
import Layout from '@/layouts/DefaultLayout.vue'
import { onMounted, ref } from 'vue';

const ipAdress = ref('')
const isBrazilian = ref(false)

const isIPAdressBrazilian = async () => {
    try {
        const response = await fetch(`https://ipapi.co/${ipAdress.value}/json/`);
        const data = await response.json();
        return data.country_code === 'BR';
    } catch (error) {
        console.error('Error fetching IP address information:', error);
        return false;
    }
}

const getIpAdress = async () => {
    try {
        const response = await fetch('https://api.ipify.org?format=json');
        const data = await response.json();
        ipAdress.value = data.ip;
        if(ipAdress.value){
            isIPAdressBrazilian().then(isIPBrazilian => {
                if (isIPBrazilian) {
                    isBrazilian.value = true
                    console.log('The user is accessing from Brazil.');
                    //chamar a mensagem para avisar o usuário que o acesso online é apenar para fora do Brasil
                } else {
                    console.log('The user is not accessing from Brazil.');
                }
            });
        }
    } catch (error) {
        console.error('Error fetching IP address:', error);
    }
}

onMounted(() => {
    getIpAdress()
})
</script>

<template>
    <Layout>
        <main>
            <div class="container py-3 py-lg-5">
                <h1 class="text-center">
                    SUBSCRIBE
                </h1>
            </div>
        </main>
    </Layout>
</template>

<style lang="css" scoped></style>