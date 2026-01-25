// THIS WAS ALL DEFAULT
//import { createApp } from 'vue'
//import { createPinia } from 'pinia'
//
//import App from './App.vue'
//import router from './router'
//
//const app = createApp(App)
//
//app.use(createPinia())
//app.use(router)
//
//app.mount('#app')

// Based on tutorial

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

// import css
import './assets/styles.css'

createApp(App).use(router).use(store).mount('#app')
