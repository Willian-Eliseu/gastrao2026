<script setup>
import Layout from '@/layouts/DefaultLayout.vue';
import ModuleComponent from '@/components/ModuleComponent.vue'
import VideoCardGrid from '@/components/VideoCardGrid.vue'
import VideoCardList from '@/components/VideoCardList.vue'
import { computed, onMounted, ref } from 'vue'
import { useSiteStore } from '@/stores/website'
import { useRouter } from 'vue-router';

const router = useRouter()
const searchQuery = ref('')
const eventStore = useSiteStore()
const displayFormat = ref('grid')
const classList = ref('col-md-6 col-lg-4 col-xl-3')
const content = ref([])

onMounted(async () => {
  await verifyLogin()
  getContent()
})

const filteredContent = computed(() => {
  const query = searchQuery.value.toLocaleLowerCase();
  return content.value
    .map(modulo => {
      const videosFiltrados = modulo.videos.filter(video =>
        video.titulo.toLowerCase().includes(query) || (video.descricao && video.descricao.toLocaleLowerCase().includes(query))
      );
      if (videosFiltrados.length > 0) {
        return {
          ...modulo,
          videos: videosFiltrados
        };
      }
      return null;
    })
    .filter(modulo => modulo !== null);
})

const getContent = async () => {
  try {
    const response = await fetch('https://eventos.tbr.com.br/apis/modules/?cliente=' + eventStore.tbreadId)
    if (response.ok) {
      const data = await response.json()
      content.value = data.dados
    } else {
      const data = await response.json()
      throw new Error(data.message)
    }
  } catch (error) {
    console.error('Erro ao carregar dados da API: ', error.message)
  }
}

const defineDisplayFormat = (format) => {
  if (format == 'grid') {
    classList.value = 'col-md-6 col-lg-4 col-xl-3'
  } else {
    classList.value = 'col-12'
  }
  displayFormat.value = format
}

const verifyLogin = async () => {
  try {
    let urlGet = `https://eventos.tbr.com.br/apis/login/?evento=${eventStore.evento}&email=${eventStore.email}`
    const response = await fetch(urlGet, {
      method: 'GET',
      headers: new Headers()
    })

    if (response.ok) {
      const data = await response.json()
      if (data.estado == 1) {
        let uNome = `${data.dados.firstname} ${data.dados.lastname}`
        let uEmail = data.dados.email
        let uId = data.dados.id
        let uCategory = data.dados.subscribe_training_center

        sessionStorage.setItem('isAuthenticated', true)
        sessionStorage.setItem('name', uNome)
        sessionStorage.setItem('email', uEmail)
        sessionStorage.setItem('userid', uId)
        sessionStorage.setItem('categoria', uCategory)
        sessionStorage.setItem('tratamento', data.dados.treatment)
        sessionStorage.setItem('ip', data.dados.ip)
        sessionStorage.setItem('city', data.dados.city)
        sessionStorage.setItem('state', data.dados.state)
        sessionStorage.setItem('enable', data.dados.enable)

        if (data.dados.enable == 0) {
          router.push({ path: '/payment' })
        }
      }
    } else {
      const data = await response.json()
      throw new Error(data.message)
    }
  } catch (error) {
    console.log(error)
  }
}
</script>

<template>
  <Layout>
    <main>
      <!-- título e descrição -->
      <section class="container py-5">
        <h2 class="text-center fw-bold text-green">
          SBGG - Distúrbios do Sono na Pessoa Idosa
        </h2>
        <p class="text-center fs-5">
          O Webinar foi transmitido ao vivo no dia 21/10/2025.
        </p>
      </section>

      <!-- ondemand -->
      <section class="container pb-5">
        <div class="p-3 border-secondary border rounded-4">
          <article class="row justify-content-between">
            <div class="col-6 col-md-4 col-lg-3">
              <input type="text" class="form-control fs-5 rounded-3 border-secondary" placeholder="Busca"
                aria-label="Busca" v-model="searchQuery" />
            </div>
            <div class="col-auto">
              <div class="btn-group" role="group" aria-label="formato de exibição">
                <button type="button" @click="defineDisplayFormat('grid')"
                  :class="displayFormat == 'grid' ? 'btn btn-secondary' : 'btn btn-outline-secondary'">
                  <font-awesome-icon icon="fa-solid fa-table" />
                </button>
                <button type="button" @click="defineDisplayFormat('list')"
                  :class="displayFormat == 'list' ? 'btn btn-secondary' : 'btn btn-outline-secondary'">
                  <font-awesome-icon icon="fa-solid fa-bars" />
                </button>
              </div>
            </div>
          </article>
          <article class="row">
            <div class="col">
              <hr />
            </div>
          </article>
          <article class="row">
            <ModuleComponent v-for="c in filteredContent" :key="c.id">
              <template v-slot:title>
                {{ c.titulo.toUpperCase() }}
              </template>

              <div v-for="(v, vi) in c.videos" :key="vi" :class="classList">
                <videoCardGrid v-if="displayFormat == 'grid'"
                  :image="'https://tbr-legacy-thumbnails.s3.sa-east-1.amazonaws.com/' + v.newid + '.jpg'"
                  :title="v.titulo" :description="v.descricao"
                  @click="$router.push({ path: 'video', query: { id: v.id, newid: v.newid } })" />
                <videoCardList v-else
                  :image="'https://tbr-legacy-thumbnails.s3.sa-east-1.amazonaws.com/' + v.newid + '.jpg'"
                  :title="v.titulo" :description="v.descricao"
                  @click="$router.push({ path: 'video', query: { id: v.id, newid: v.newid } })" />
              </div>
            </ModuleComponent>
          </article>
        </div>
      </section>
    </main>
  </Layout>
</template>
