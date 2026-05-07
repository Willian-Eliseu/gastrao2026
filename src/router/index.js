import { useSiteStore } from '@/stores/website'
import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/HomeView.vue'),
    },
    {
      path: '/support',
      name: 'support',
      component: () => import('@/views/SupportView.vue'),
    },
    {
      path: '/subscribe',
      name: 'subscribe',
      component: () => import('@/views/SubscribeView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
    },
    {
      path: '/content',
      name: 'content',
      component: () => import('@/views/ContentView.vue'),
      meta: {
        requiresAuth: true,
      }
    },
    {
      path: '/live',
      name: 'live',
      component: () => import('@/views/LiveView.vue'),
      meta: {
        requiresAuth: true,
      }
    },
    {
      path: '/ondemand',
      name: 'ondemand',
      component: () => import('@/views/OndemandView.vue'),
      meta: {
        requiresAuth: true,
      }
    },
    {
      path: '/player',
      name: 'player',
      component: () => import('@/views/PlayerView.vue'),
      meta: {
        requiresAuth: true,
      }
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth'
      }
    } else {
      if (savedPosition) {
        return savedPosition
      } else {
        return {
          top: 0
        }
      }
    }
  },
})

router.beforeEach((to, from) => {
  const siteStore = useSiteStore()
  if (to.meta.requiresAuth && !siteStore.isAuthenticated) {
    return {
      name: 'login',
      query: {
        redirect: to.fullPath
      },
    }
  }
})

export default router
