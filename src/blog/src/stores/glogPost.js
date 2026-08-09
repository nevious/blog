import { ref } from 'vue'
import { defineStore } from 'pinia'
import { fetchPosts, fetchPostBySlug } from '@/api/glogPosts'

export const usePostStore = defineStore('glogStore', () => {
	const posts = ref([])
	const currentPost = ref(null)
	const error = ref(null)
	const isLoading = ref(false)

	function getPostIndex() {
		return posts.value.findIndex(post => post.slug == currentPost.value.meta.slug)
	}

	function getNextPost() {
		const index = getPostIndex()
		if ( index >= 0 && index < posts.value.length - 1){
			return posts.value.at(index+1)
		}
		return null
	}

	function getPreviousPost(){
		const index = getPostIndex()
		if ( index > 0 ) {
			return posts.value.at(index-1)
		}
		return null
	}

	async function loadPosts() {
		error.value = null
		isLoading.value = true
		try {
			posts.value = await fetchPosts()
		} catch (err) {
			error.value = err
		} finally {
			isLoading.value = false
		}
	}

	async function loadPostBySlug(slug) {
		error.value = null
		isLoading.value = true
		try {
			currentPost.value = await fetchPostBySlug(slug)
		} catch (err) {
			error.value = err
		} finally {
			isLoading.value = false
		}
	}

	return {
		posts,
		currentPost,
		loadPosts,
		loadPostBySlug,
		getNextPost,
		getPreviousPost,
		error,
		isLoading
	}
})
