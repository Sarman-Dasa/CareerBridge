/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import layoutsPlugin from '@/plugins/layouts'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import '@core-scss/template/index.scss'
import '@styles/styles.scss'
import { createPinia } from 'pinia'
import { createApp } from 'vue'
import { useUserStore } from './pages/user-profile/useUserStore'
import { formatDate } from './utils/dateFormatter'

loadFonts()


// Create vue app
const app = createApp(App)

// Add the date formatter as a global property
app.config.globalProperties.$formatDate = formatDate;

// Use plugins
app.use(vuetify)
app.use(createPinia())
app.use(router)
app.use(layoutsPlugin)


if(localStorage.getItem('userData') && localStorage.getItem('accessToken')) {
  const userStore = useUserStore();

  let userData = JSON.parse(localStorage.getItem('userData'));
  userStore.setUser(userData);
}


// Mount vue app
app.mount('#app')
