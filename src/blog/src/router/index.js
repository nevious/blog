import { createRouter, createWebHistory } from 'vue-router'

// Import View Compopnents
import BlogHome from '../views/BlogHome.vue'
import BlogPost from '../views/BlogPost.vue'

const routes = [
	{
		path: '/',
		name: 'home',
		component: BlogHome
	},
	{
		path: '/posts/:slug',
		name: 'post',
		component: BlogPost
	}
]

//const router = createRouter({
//  history: createWebHistory(import.meta.env.BASE_URL),
//  routes: routes
//})

const router = createRouter({
		history: createWebHistory(),
		routes
	}
)

export default router
