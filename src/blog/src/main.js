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

import App from './App.vue'
import { createApp } from 'vue'
import router from './router'
import { createPinia } from 'pinia'

// import css
import './assets/styles.css'

createApp(
	App
).use(
	router
).use(
	createPinia()
).mount('#app')
