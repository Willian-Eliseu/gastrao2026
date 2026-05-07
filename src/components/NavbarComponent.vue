<script setup>
import { useSiteStore } from '@/stores/website';
import { RouterLink, useRouter } from 'vue-router';
import { onMounted, ref } from 'vue';

const siteStore = useSiteStore();
const router = useRouter();
const fixedNavbar = ref(false);

const logOut = () => {
    siteStore.logout()
    localStorage.removeItem('tokenJwt')
    router.push('/')
}

const onScroll = () => {
    window.addEventListener('scroll', () => {
        if(window.scrollY > 100) {
            fixedNavbar.value = true
        } else {
            fixedNavbar.value = false
        }
    })
}

onMounted(() => {
    onScroll()
})
</script>

<template>
    <header>
        <nav :class="['navbar navbar-expand-xl bg-light shadow', { 'fixed-top': fixedNavbar }]" data-bs-theme="light">
            <div class="container py-1">
                <a class="navbar-brand fw-bold fs-3" href="/">
                    <!-- logo -->
                    <img src="@/assets/img/logo.png" alt="Logo" height="40" class="d-inline-block align-text-top me-2">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav fw-bold">
                        <li class="nav-item ms-lg-2">
                            <RouterLink class="nav-link" to="/">Home</RouterLink>
                        </li>
                        <li class="nav-item ms-lg-2" v-if="!siteStore.isAuthenticated">
                            <RouterLink class="nav-link" to="/subscribe">Subscribe</RouterLink>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <RouterLink class="nav-link" to="/support">Support</RouterLink>
                        </li>
                        <li class="nav-item ms-lg-2" v-if="siteStore.isAuthenticated">
                            <RouterLink class="nav-link" to="/content">Content</RouterLink>
                        </li>
                        <li class="nav-item ms-lg-3 d-grid" v-else>
                            <RouterLink class="btn btn-primary bg-gradient rounded-pill px-4 fs-5 fw-bold" to="/login">
                                Login
                            </RouterLink>
                        </li>
                        <li class="nav-item ms-lg-3 d-grid" v-if="siteStore.isAuthenticated">
                            <button class="btn btn-secondary bg-gradient rounded-pill px-4 fs-5 fw-bold" @click="logOut">
                                Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>

<style lang="css" scoped>

</style>