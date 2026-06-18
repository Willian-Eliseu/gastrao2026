import { defineStore } from "pinia";
const sessionData = JSON.parse(sessionStorage.getItem('userData') ?? '{}')

export const useSiteStore = defineStore('siteStore', {
  state: () => ({
    isAuthenticated: sessionData.isAuthenticated ?? false,
    isEnabled: sessionData.isEnabled ?? false,
    firstname: sessionData.firstname ?? '',
    lastname: sessionData.lastname ?? '',
    email: sessionData.email ?? '',
    userId: sessionData.id ?? 0,
    userHash: sessionData.userHash ?? '',
    userCity: sessionData.userCity ?? '',
    userState: sessionData.userState ?? '',
    userIp: sessionData.userIp ?? '',
    tbreadId: 1039,
    eventId: 398,
  }),
  actions: {
    login(userData){
      this.isAuthenticated = true
      this.isEnabled = userData.isEnabled
      this.firstname = userData.firstname
      this.lastname = userData.lastname
      this.email = userData.email
      this.userId = userData.userId
      this.userHash = userData.userHash
      this.userCity = userData.userCity
      this.userState = userData.userState
      this.userIp = userData.userIp
      sessionStorage.setItem('userData', JSON.stringify(userData))
    },
    logout(){
      this.isAuthenticated = false
      this.firstname = ''
      this.lastname = ''
      this.email = ''
      this.userId = 0
      this.userHash = ''
      this.userCity = ''
      this.userState = ''
      this.userIp = ''
      sessionStorage.removeItem('userData')
    },
  },
})