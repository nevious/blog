import { ref } from 'vue'
import { defineStore } from 'pinia'
import { fetchPosts, fetchPostBySlug } from '@/api/glogPosts'

export const usePostStore = defineStore('glogStore', () => {
	const posts = ref([])
	const currentPost = ref(null)
	let error = ref(null)

	function getPostIndex() {
		return posts.value.findIndex(post => post.slug == currentPost.value.meta.slug)
	}

	function getNextPost() {
		const index = getPostIndex()
		if ( index >= 0 && index < posts.value.length - 1){
			return posts.value.at(index+1)
		}
		return posts.value.at(0)
	}

	function getPreviousPost(){
		const index = getPostIndex()
		if ( index > 0 ) {
			return posts.value.at(index-1)
		}
		return posts.value.at(-1)
	}

	async function loadPosts() {
		try {
			posts.value = await fetchPosts()
		} catch (err) {
			error = err
		}
	}

	async function loadPostBySlug(slug) {
		try {
			currentPost.value = await fetchPostBySlug(slug)
		} catch (err) {
			error = err
		}
	}

	return {
		posts,
		currentPost,
		loadPosts,
		loadPostBySlug,
		getNextPost,
		getPreviousPost
	}
})
