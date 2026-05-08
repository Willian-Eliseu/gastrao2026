<script setup>
import Layout from '@/layouts/DefaultLayout.vue'
import { useSiteStore } from '@/stores/website'
import api from '@/services/api'
import { onMounted, ref } from 'vue'
import { useAlert } from '@/services/alertService'

const { showAlert } = useAlert()
const isSendingSupportRequest = ref(false)
const siteStore = useSiteStore()
const supportData = ref({})

const getIp = async () => {
    try {
        const response = await fetch('https://api.ipify.org?format=json');
        const data = await response.json();
        supportData.value.ip = data.ip;
    } catch (error) {
        console.error('Erro ao obter o IP:', error);
    }
};

const handleSubmitSupport = async () => {
    isSendingSupportRequest.value = true
    const payload = supportData.value
    payload.evento = siteStore.eventId
    payload.pagina = 'Gastrao 2026'

    try {
        const response = await api.post('/support/', payload)
        const data = response.data
        if (data.estado == 0) {
            throw new Error(data.message)
        }

        showAlert({
            title: 'Success',
            message: 'The message was sent successfully.',
            type: 'success'
        })

        supportData.value = {}
    } catch (error) {
        showAlert({
            title: 'Error',
            message: error.message,
            type: 'error'
        })
    } finally {
        isSendingSupportRequest.value = false
    }
}

onMounted(() => {
    getIp()
    supportData.value.tratamento = ''
})
</script>

<template>
    <Layout>
        <main>
            <section class="bg-default" style="min-height: calc(100dvh - (299px + 76px));">
                <div class="container py-3 py-lg-5">
                    <!-- title -->
                    <div class="row">
                        <div class="col">
                            <h1 class="text-center mb-3 text-principal fw-bold" v-reveal="'bottom'">Support</h1>
                            <h3 class="text-center mb-3 text-principal fw-semibold" v-reveal="'bottom'">35º Digestive
                                and Therapeutic Endoscopy Course</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-9 mx-auto">
                            <form @submit.prevent="handleSubmitSupport">
                                <div class="container">
                                    <!-- formulário com duas colunas com treatment, name, email e message -->
                                    <div class="row">
                                        <div class="col">
                                            <p class="text-principal text-center text-lg-start" v-reveal="'bottom'">
                                                Fields marked with * are required
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6 mb-3 mb-lg-0">
                                            <!-- treatment -->
                                            <div class="form-group mb-3" v-reveal="'bottom'">
                                                <label for="supportTreatment"
                                                    class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup> Treatment:</label>
                                                <select name="supportTreatment" id="supportTreatment"
                                                    v-model="supportData.tratamento"
                                                    class="form-select border-1 border-secondary rounded-0 fs-5"
                                                    required>
                                                    <option value="">Select your treatment</option>
                                                    <option value="Doctor">Doctor</option>
                                                    <option value="Professor">Professor</option>
                                                </select>
                                            </div>
                                            <!-- name -->
                                            <div class="form-group mb-3" v-reveal="'bottom'">
                                                <label for="supportName" class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup> Name:</label>
                                                <input type="text" id="supportName" v-model="supportData.nome"
                                                    class="form-control border-1 border-secondary rounded-0 fs-5"
                                                    placeholder="Enter your name" required>
                                            </div>
                                            <!-- email -->
                                            <div class="form-group" v-reveal="'bottom'">
                                                <label for="supportEmail" class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup> Email:</label>
                                                <input type="email" id="supportEmail" v-model="supportData.email"
                                                    class="form-control border-1 border-secondary rounded-0 fs-5"
                                                    placeholder="Enter your email" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- message -->
                                            <div class="form-group mb-3" v-reveal="'bottom'">
                                                <label for="supportMessage" class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup> Message:</label>
                                                <textarea name="supportMessage" id="supportMessage"
                                                    v-model="supportData.mensagem"
                                                    class="form-control border-1 border-secondary rounded-0 fs-5"
                                                    required style="min-height: 224px;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-auto mx-auto d-grid" v-reveal="'bottom'">
                                            <button type="submit" class="btn btn-default fs-5 rounded-4 px-5"
                                                :disabled="isSendingSupportRequest">
                                                <font-awesome-icon icon="fa-solid fa-spinner" spin
                                                    v-if="isSendingSupportRequest" />
                                                {{ isSendingSupportRequest ? 'Submitting... please wait' : 'Submit' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </Layout>
</template>

<style lang="css" scoped></style>