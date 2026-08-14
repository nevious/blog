import { createRouter, createWebHistory } from 'vue-router'
import { usePostStore } from '@/stores/glogPost'
import { useSiteStore } from '@/stores/site'

// Import View Compopnents lazily
const BlogHome = () => import('@/views/BlogHome.vue')
const BlogPost = () => import('@/views/BlogPost.vue')
const BlogAbout = () => import('@/views/BlogAbout.vue')
const NotFound = () => import('@/views/NotFound.vue')

const routes = [
	{
		path: '/',
		name: 'home',
		component: BlogHome,
		meta: {title: 'Home'}
	},
	{
		path: '/posts/:slug',
		name: 'post',
		component: BlogPost,
		beforeEnter: async (to, from) => {
			const postStore = usePostStore()
			if (postStore.posts.length === 0) {
				await postStore.loadPosts()
			}

			const exists = postStore.posts.find(post => post.slug === to.params.slug)
			if (!exists) {
				return {'name': 'not-found'}
			}
		},
	},
	{
		path: '/about',
		name: 'about',
		component: BlogAbout,
		meta: {title: "About"}
	},
	{
		path: '/not-found',
		name: 'not-found',
		component: NotFound,
		meta: {title: "Not found"}
	}


]

const router = createRouter({
		history: createWebHistory(),
		routes
	}
)

router.afterEach((to) => {
	const store = useSiteStore()
	const postStore = usePostStore()

	// If targeting a post, it's ensured that it exissts via the router
	// We can therefore grab the title and update the browser tab
	if (to.name === 'post') {
		const post = postStore.posts.find(post => post.slug === to.params.slug)
		store.updatePageTitle(`${store.siteTitle} - ${post.title}`)
	} else {
		store.updatePageTitle(`${store.siteTitle} - ${to.meta.title}`)
	}

	document.title = store.pageTitle
})

export default router
