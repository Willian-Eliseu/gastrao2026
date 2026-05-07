<script setup>
import Layout from '@/layouts/DefaultLayout.vue'
import { ref } from 'vue'
const email = ref('')
const isSendingLoginRequest = ref(false)

const handleLogin = () => {
    isSendingLoginRequest.value = true
    console.log('Login attempted with email:', email.value)
    // Here you would typically handle authentication logic, such as sending a request to your backend.
}



</script>

<template>
    <Layout>
        <main>
            <section class="bg-default" style="min-height: calc(100dvh - (241px + 76px));">
                <div class="container py-3 py-lg-5">
                    <!-- title -->
                    <div class="row">
                        <div class="col">
                            <h1 class="text-center mb-3 text-principal fw-bold" v-reveal="'bottom'">Login</h1>
                            <h3 class="text-center mb-3 text-principal fw-semibold" v-reveal="'bottom'">53º Gastrão</h3>
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
                                                <input type="email" id="email" v-model="email"
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