<template>
	<nav class="postPagerNav">
		<router-link :to="`/posts/${previousPost.slug}`">
			<Button class="min-width" title="Previous" :description="previousPost.title" />
		</router-link>

		<router-link :to="`/posts/${nextPost.slug}`">
			<Button class="min-width right-bound" title="Next" :description="nextPost.title" />
		</router-link>
	</nav>
</template>

<script setup>
	import { onMounted, computed, watch } from 'vue'
	import { usePostStore } from '@/stores/glogPost'
	import { useRoute } from 'vue-router'
	import Button from './Button.vue'

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
</script>

<style scoped>
.postPagerNav {
	margin-top: 2rem;
	display: flex;
	justify-content: space-between;
}

.min-width {
	min-width: 5rem;
	margin: 0;
}

.right-bound {
	text-align: right;
}

a:link,
a:visited,
a:hover,
a:active {
	text-decoration: none;
}
</style>
