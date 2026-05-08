import { defineStore } from "pinia";
const sessionData = JSON.parse(sessionStorage.getItem('userData') ?? '{}')

export const useSiteStore = defineStore('siteStore', {
  state: () => ({
    isAuthenticated: sessionData.enabled ?? false,
    isEnabled: sessionData.isEnabled ?? false,
    firstname: sessionData.firstname ?? '',
    lastname: sessionData.lastname ?? '',
    email: sessionData.email ?? '',
    userId: sessionData.id ?? 0,
    tbreadId: 0,
    eventId: 0,
  }),
  actions: {
    login(userData){
      this.isAuthenticated = true
      this.isEnabled = userData.isEnabled
      this.firstname = userData.firstname
      this.lastname = userData.lastname
      this.email = userData.email
      this.userId = userData.id
      sessionStorage.setItem('userData', JSON.stringify(userData))
    },
    logout(){
      this.isAuthenticated = false
      this.firstname = ''
      this.lastname = ''
      this.email = ''
      this.userId = 0
      sessionStorage.removeItem('userData')
    },
  },
})