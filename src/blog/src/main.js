import App from './App.vue'
import { createPinia } from 'pinia'
import { createApp } from 'vue'
import router from './router'

// import css
import './assets/styles.css'

createApp(
	App
).use(
	createPinia()
).use(
	router
).mount('#app')
