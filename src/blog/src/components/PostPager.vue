<template>
	<nav class="postPagerNav">
		<router-link :to="`/posts/${previousPost.slug}`"><< {{previousPost.title }}</router-link>
		<router-link :to="`/posts/${nextPost.slug}`"> {{ nextPost.title }} >></router-link>
	</nav>
</template>

<script setup>
	import { onMounted, computed, watch } from 'vue'
	import { usePostStore } from '@/stores/glogPost'
	import { useRoute } from 'vue-router'

	const postStore = usePostStore()
	const route = useRoute()

	const nextPost = computed(() => postStore.getNextPost())
	const previousPost = computed(() => postStore.getPreviousPost())

	watch(
		() => route.params.slug,
		(newSlug, oldSlug) => {
			const nextPost = postStore.getNextPost()
			const previousPost = postStore.getPreviousPost()
		}
	)

	console.log('posts: %o', postStore.posts)
	console.log("nextPost: %o", nextPost.value)
	console.log("previousPost: %o", previousPost.value)
</script>

<style scoped>
.postPagerNav {
	display: flex;
	justify-content: space-between;
}
</style>
