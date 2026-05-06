import { defineStore } from "pinia";
const sessionData = JSON.parse(localStorage.getItem('userData') ?? '{}')

export const useSiteStore = defineStore('siteStore', {
  state: () => ({
    isAuthenticated: sessionData.enabled ?? false,
    firstname: sessionData.firstname ?? '',
    lastname: sessionData.lastname ?? '',
    email: sessionData.email ?? '',
    userId: sessionData.id ?? 0,
    role: sessionData.role ?? ''
  }),
  actions: {
    login(userData){
      this.isAuthenticated = true
      this.firstname = userData.firstname
      this.lastname = userData.lastname
      this.email = userData.email
      this.userId = userData.id
      this.role = userData.role
      localStorage.setItem('userData', JSON.stringify(userData))
    },
    logout(){
      this.isAuthenticated = false
      this.firstname = ''
      this.lastname = ''
      this.email = ''
      this.userId = 0
      this.role = ''
      localStorage.removeItem('userData')
    },
  },
})