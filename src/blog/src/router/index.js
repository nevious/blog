import { createRouter, createWebHistory } from 'vue-router'

// Import View Compopnents
import BlogHome from '../views/BlogHome.vue'
import BlogPost from '../views/BlogPost.vue'
import BlogAbout from '../views/BlogAbout.vue'

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
	},
	{
		path: '/about',
		name: 'about',
		component: BlogAbout
	}
]

const router = createRouter({
		history: createWebHistory(),
		routes
	}
)

export default router
