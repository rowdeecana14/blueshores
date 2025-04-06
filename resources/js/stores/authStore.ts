import { defineStore } from 'pinia';
import { Inertia } from '@inertiajs/inertia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    errors: null,
  }),
  actions: {
    async login(credentials: { email: string, password: string }) {
      try {
        const response = await Inertia.post('/login', credentials);
        this.user = response.data.user;
        this.errors = null;
      } catch (error) {
        this.errors = error.response?.data.errors;
      }
    },
  },
});
