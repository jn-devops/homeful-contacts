import { defineStore } from 'pinia'

export const useFormStore = defineStore('form', {
  state: () => ({
    initialAccess: true,
  }),
})
