<script setup>
import Layout from '@/layouts/DefaultLayout.vue'
import { reactive, ref } from 'vue'
import api from '@/services/api'
import { useRouter, RouterLink } from 'vue-router';
import { useAlert } from '@/services/alertService'
import { useSiteStore } from '@/stores/website';

const { showAlert } = useAlert()
const siteStore = useSiteStore()
const router = useRouter()
const isSendingLoginRequest = ref(false)

const formLogin = reactive({
    email: '',
})

const handleLogin = async () => {
    isSendingLoginRequest.value = true
    
    try {
        const response = await api.get(`/login/?evento=${siteStore.eventId}&email=${formLogin.email}`)
        const data = response.data

        if(data.code == 0){
            throw new Error(data.message)        
        }

        let userData = data.dados
        siteStore.login({
            isAuthenticated: 'true',
            isEnabled: (userData.enabled == 1) ? true : false,
            firstname: userData.firstname,
            lastname: userData.lastname,
            email: userData.email,
            userId: userData.id,
            userHash: userData.control_hash,
            userCity: userData.ip_city,
            userState: userData.ip_subdivision,
            userIp: userData.ip
        })

        showAlert({
            title: 'Success',
            message: 'Welcome back!',
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
        isSendingLoginRequest.value = false
    }
}

</script>

<template>
    <Layout>
        <main>
            <section class="bg-default" style="min-height: calc(100dvh - 349px);">
                <div class="container py-3 py-lg-5">
                    <!-- title -->
                    <div class="row">
                        <div class="col">
                            <h1 class="text-center mb-3 text-principal fw-bold" v-reveal="'bottom'">Login</h1>
                            <h3 class="text-center mb-3 text-principal fw-semibold" v-reveal="'bottom'">35th Therapeutic Digestive Endoscopy Course</h3>
                        </div>
                    </div>
                    <!-- form -->
                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <form @submit.prevent="handleLogin">
                                <div class="container">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <div class="form-group" v-reveal="'bottom'">
                                                <label for="email" class="text-principal mb-1">Email:</label>
                                                <input type="email" id="email" v-model="formLogin.email"
                                                    class="form-control border-1 border-secondary rounded-0 fs-5"
                                                    placeholder="Enter your email" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <p class="text-principal" v-reveal="'bottom'">
                                                Don't have an account? <RouterLink to="/subscribe" class="text-required fw-semibold text-decoration-none">Subscribe here</RouterLink>.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-auto mx-auto d-grid" v-reveal="'bottom'">
                                            <button type="submit" class="btn btn-default fs-5 rounded-4 px-5" :disabled="isSendingLoginRequest">
                                                <font-awesome-icon icon="fa-solid fa-spinner" spin v-if="isSendingLoginRequest" />
                                                {{ isSendingLoginRequest ? 'Submitting... please wait' : 'Submit' }}
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