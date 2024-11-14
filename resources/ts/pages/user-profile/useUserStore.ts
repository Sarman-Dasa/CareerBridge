import { defineStore } from "pinia";

export const useUserStore = defineStore("userStore", {
  state: () => ({
    user:null
  }),

  actions:{
    setUser(userData: any) {
        this.user = userData;
    }
  }
});
