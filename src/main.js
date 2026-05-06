import './assets/css/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

import App from './App.vue'
import { vReveal } from './directives/vReveal'
import router from './router'

import icons from '@/services/fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(...icons)

const app = createApp(App)

app.directive('reveal', vReveal)
app.component('font-awesome-icon', FontAwesomeIcon)
app.use(createPinia())
app.use(router)

app.mount('#app')