<script setup>
import Layout from '@/layouts/DefaultLayout.vue'
import { onMounted, ref } from 'vue'
import { useAlert } from '@/services/alertService'
import { useSiteStore } from '@/stores/website'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const { showAlert } = useAlert()
const siteStore = useSiteStore()
const ipAdress = ref('')
const isBrazilian = ref(false)
const formularyData = ref({})
const router = useRouter()
const isSubmiting = ref(false)
const emailConfirmed = ref(true)

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
        formularyData.value.dataIp = ipAdress.value

        if (ipAdress.value) {
            isIPAdressBrazilian().then(isIPBrazilian => {
                if (isIPBrazilian) {
                    isBrazilian.value = true
                    console.log('The user is accessing from Brazil.');
                    showAlert({
                        title: 'Atenção',
                        message: 'A transmissão via internet do 35º Curso de Endoscopia Digestiva Terapêutica é liberada apenas para outros países. Você será redirecionado para o site oficial do Gastrão.',
                        type: 'warning'
                    })
                    //descomentar depois
                    //window.location.href = 'https://gastrao.org.br'
                } else {
                    console.log('The user is not accessing from Brazil.');
                }
            });
        }
    } catch (error) {
        console.error('Error fetching IP address:', error);
    }
}

const handleSubmit = () => {
    if (formularyData.value.dataEmail != formularyData.value.confirm_email) {
        showAlert({
            title: 'Attention',
            message: 'The emails must be the same',
            type: 'warning'
        })
        return
    }

    isSubmiting.value = true
    const payload = formularyData.value
    payload.dataEvento = siteStore.eventId
    payload.dataPagina = 'Gastrão 2026';
    payload.dataEnable = 1;

    try {
        const response = api.post('/subscribe/', payload)
        const data = response.data

        if(data.estado != 1){
            throw new Error(data.message)
        }

        let userData = data.dados
        siteStore.login({
            isEnabled: (userData.enabled == 1) ? true : false,
            firstname: userData.firstname,
            lastname: userData.lastname,
            email: userData.email,
            userId: userData.id,
        });

        showAlert({
            title: 'Success',
            message: 'Registration successful! Soon you will receive a confirmation email.',
            type: 'success'
        })

        router.push({
            name: 'content'
        })
    } catch (error) {
        showAlert({
            title: 'Error',
            message: error.message,
            type: 'error'
        })
    } finally {
        isSubmiting.value = false
    }
}

const emailConfirmation = () => {
    if (formularyData.value.dataEmail != formularyData.value.confirm_email) {
        emailConfirmed.value = false
    } else {
        emailConfirmed.value = true
    }
}

onMounted(() => {
    getIpAdress()
    formularyData.value = {
        dataContatoTbr: false,
        dataTreatment: '',
    }
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
                            <h1 class="text-center mb-3 text-principal fw-bold" v-reveal="'bottom'">Subscribe</h1>
                            <h3 class="text-center mb-3 text-principal fw-semibold" v-reveal="'bottom'">35º Digestive
                                and Therapeutic Endoscopy Course</h3>
                        </div>
                    </div>

                    <!-- form -->
                    <div class="row">
                        <div class="col-lg-9 mx-auto">
                            <form @submit.prevent="handleSubmit">
                                <div class="container">
                                    <div class="row mb-lg-3">
                                        <div class="col-md-3 mb-3 mb-md-0" v-reveal="'bottom'">
                                            <div class="form-group">
                                                <label for="treatment" class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup>Treatment</label>
                                                <select name="treatment" id="treatment"
                                                    v-model="formularyData.treatment"
                                                    class="form-select border-1 border-secondary rounded-0 fs-5"
                                                    required>
                                                    <option value="" disabled>Select your treatment</option>
                                                    <option value="Doctor">Doctor</option>
                                                    <option value="Professor">Professor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-9 mb-3 mb-md-0" v-reveal="'bottom'">
                                            <div class="form-group">
                                                <label for="name" class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup>Full Name</label>
                                                <input type="text" id="name" v-model="formularyData.name"
                                                    class="form-control border-1 border-secondary rounded-0 fs-5"
                                                    placeholder="Enter your name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-lg-3">
                                        <div class="col-md-6 mb-3 mb-md-0" v-reveal="'bottom'">
                                            <div class="form-group">
                                                <!-- cellphone -->
                                                <label for="cellphone" class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup>Cellphone</label>
                                                <input type="text" id="cellphone" v-model="formularyData.cellphone"
                                                    class="form-control border-1 border-secondary rounded-0 fs-5"
                                                    placeholder="Enter your cellphone" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3 mb-md-0" v-reveal="'bottom'">
                                            <div class="form-group">
                                                <!-- country -->
                                                <label for="country" class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup>Country</label>
                                                <input type="text" id="country" v-model="formularyData.country"
                                                    class="form-control border-1 border-secondary rounded-0 fs-5"
                                                    placeholder="Enter your country" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-lg-3">
                                        <div class="col-md-6 mb-3 mb-md-0" v-reveal="'bottom'">
                                            <div class="form-group">
                                                <!-- email -->
                                                <label for="email" class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup>Email</label>
                                                <input type="email" id="email" v-model="formularyData.dataEmail"
                                                    class="form-control border-1 border-secondary rounded-0 fs-5"
                                                    placeholder="Enter your email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3 mb-md-0" v-reveal="'bottom'">
                                            <div class="form-group">
                                                <!-- email confirmation -->
                                                <label for="email_confirmation"
                                                    class="text-principal fw-semibold fs-5"><sup
                                                        class="text-required">*</sup>Confirm Email</label>
                                                <input type="email" id="email_confirmation"
                                                    v-model="formularyData.confirm_email"
                                                    :class="['form-control border-1 border-secondary rounded-0 fs-5', emailConfirmed ? 'border-secondary' : 'border-danger']"
                                                    placeholder="Confirm your email" @input="emailConfirmation"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-lg-3">
                                        <div class="col" v-reveal="'bottom'">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input border-1 border-secondary border rounded-0"
                                                    type="checkbox" id="contactConsent"
                                                    v-model="formularyData.contactConsent">
                                                <label class="form-check-label fs-5 text-principal"
                                                    for="contactConsent">
                                                    I agree to receive event information as outlined in <a
                                                        href="https://privacidade.tbr.com.br/" target="_blank"
                                                        class="fw-semibold text-decoration-none text-required">TBR's
                                                        privacy policy</a>.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p class="fs-5 mb-2 text-principal" v-reveal="'bottom'">
                                                By subscribing you confirm, the veracity of the information provided.
                                            </p>
                                            <p class="fs-5 text-principal" v-reveal="'bottom'">
                                                A confirmation email will be sent to you with further instructions.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4 mx-auto d-grid" v-reveal="'bottom'">
                                            <button type="submit" class="btn btn-default fs-5 rounded-4 px-5"
                                                :disabled="isSubmiting">
                                                <font-awesome-icon icon="fa-solid fa-spinner" spin v-if="isSubmiting" />
                                                {{ isSubmiting ? 'Submitting... please wait' : 'Subscribe' }}
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