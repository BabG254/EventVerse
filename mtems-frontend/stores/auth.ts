import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null as string | null,
    user: null as any
  }),

  actions: {
    async login(email: string, password: string) {
      // Mock successful login for testing
      if (email === 'admin@test.com' && password === 'password123') {
        this.token = 'mock-token'
        this.user = {
          id: 1,
          name: 'Admin ',
          email: email
        }
        return true
      }
      return false
    },

    logout() {
      this.token = null
      this.user = null
    }
  },

  persist: true
})