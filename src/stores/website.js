import { defineStore } from "pinia";
const sessionData = JSON.parse(sessionStorage.getItem('userData') ?? '{}')

export const useSiteStore = defineStore('siteStore', {
  state: () => ({
    isAuthenticated: sessionData.enabled ?? false,
    firstname: sessionData.firstname ?? '',
    lastname: sessionData.lastname ?? '',
    email: sessionData.email ?? '',
    userId: sessionData.id ?? 0,
    role: sessionData.role ?? '',
    tbreadId: 0,
    eventId: 0,
  }),
  actions: {
    login(userData){
      this.isAuthenticated = true
      this.firstname = userData.firstname
      this.lastname = userData.lastname
      this.email = userData.email
      this.userId = userData.id
      this.role = userData.role
      sessionStorage.setItem('userData', JSON.stringify(userData))
    },
    logout(){
      this.isAuthenticated = false
      this.firstname = ''
      this.lastname = ''
      this.email = ''
      this.userId = 0
      this.role = ''
      sessionStorage.removeItem('userData')
    },
  },
})