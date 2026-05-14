<script setup>
import Layout from '@/layouts/DefaultLayout.vue'
</script>

<template>
    <Layout>
        <main>
            <!-- hero -->
            <section class="bg-hero align-items-center d-flex">
                <div class="container py-3 py-lg-5">
                    <div class="row">
                        <div class="col-lg-6 align-self-center mb-3 mb-lg-0">
                            <img src="@/assets/img/hero_front.png" alt="" class="img-fluid" v-reveal="'bottom'">
                        </div>
                        <div class="col-lg-6">
                            <p class="text-principal mb-0 tds p-3 p-lg-0" v-reveal="'bottom'">
                                <b>Gastrão</b> marks over five decades of promoting internationally excellent scientific
                                updates in Brazil. 
                                
                            </p>
                            <p class="text-principal mb-0 tds p-3 p-lg-0" v-reveal="'bottom'">
                                In <b>2026</b>, it reaffirms its commitment to quality education and
                                the
                                exchange of experiences in clinical, surgical, and scientific fields.
                            </p>
                            <p class="text-principal mb-0 tds p-3 p-lg-0" v-reveal="'bottom'">
                                Established as the largest event on the digestive system in the country, Gastrão
                                attracts thousands of participants at each edition — including renowned specialists and
                                professionals seeking knowledge about the latest innovations in the field.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- save the date -->
            <section class="overflow-hidden">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col p-0">
                            <div class="marquee-wrapper">
                                <img src="@/assets/img/save_the_date.png" alt="Save the Date" class="img-fluid infinite-scroll">
                                <img src="@/assets/img/save_the_date.png" alt="Save the Date" class="img-fluid infinite-scroll">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- call to action -->
            <section class="bg-default">
                <div class="container py-3 py-lg-5 px-4 px-lg-0">
                    <div class="row">
                        <div class="col">
                            <h2 class="text-center mb-4 text-principal fw-bold" v-reveal="'bottom'">Join us in the <br> <span class="fs-1">35th Therapeutic Digestive Endoscopy Course</span> <br> at Gastrão 2026!</h2>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-md-auto mb-3 mb-lg-0 align-self-center">
                            <img src="@/assets/img/person_global.png" alt="" class="w-100 rounded-4 mx-auto d-block" v-reveal="'bottom'" style="max-width: 350px;">
                            <RouterLink to="/subscribe" class="btn btn-primary mt-3 d-block text-center rounded-4" v-reveal="'bottom'">Register Now</RouterLink>
                        </div>
                        <div class="col p-3 p-lg-0 ps-lg-5 align-self-center">
                            <p class="text-principal mb-2 fw-bold tds" v-reveal="'bottom'">
                                Live cases from Clinic Hospital of University of Sao Paulo (HC-FMUSP), Brazil.
                            </p>
                            <p class="text-principal mb-2 tds" v-reveal="'bottom'">
                                Don't miss the opportunity to be part of <b>35th Therapeutic Digestive Endoscopy Course</b> at Gastrão 2026! Join us for an unforgettable
                                experience filled with knowledge, networking, and innovation in the field of digestive
                                health.
                            </p>
                            <p class="text-principal mb-2 tds" v-reveal="'bottom'">
                                Register now to participate via internet!
                            </p>
                            <p class="text-principal mb-2 tds" v-reveal="'bottom'">
                                Access to the 35th Therapeutic Digestive Endoscopy Course is now available online for participants outside of Brazil.
                            </p>
                            <p class="text-principal mb-2 tds" v-reveal="'bottom'">
                                If you are in Brazil, acces the registration form on the official website of the event: <a href="https://gastrao.org.br/" target="_blank" class="text-principal">gastrao.org.br</a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </Layout>
</template>

<style lang="css" scoped>
.bg-hero {
    background: url('@/assets/img/hero.png') no-repeat center center;
    background-size: cover;
    min-height: 50vh;
}

.overflow-hidden {
    overflow: hidden;
    width: 100%;
}

.marquee-wrapper {
    display: flex;
    width: max-content;
}

.infinite-scroll {
    animation: slideLeft 20s linear infinite;
    white-space: nowrap;
}

@keyframes slideLeft {
    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(-100%);
    }
}

.marquee-wrapper:hover .infinite-scroll {
    animation-play-state: paused;
}
</style>