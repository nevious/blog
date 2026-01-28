<template>
	<nav class="postPagerNav">
		<router-link :to="`/posts/${previousPost.slug}`">Previous ({{previousPost.title }})</router-link>
		<router-link :to="`/posts/${nextPost.slug}`">Next ({{ nextPost.title }})</router-link>
	</nav>
</template>

<script setup>
	import { onMounted, computed } from 'vue'
	import { usePostStore } from '@/stores/posts'
	import { useRoute } from 'vue-router'

	const postStore = usePostStore()
	const route = useRoute()

	const nextPost = computed(() => postStore.getNextPost())
	const previousPost = computed(() => postStore.getPreviousPost())
</script>

<style scoped>
.postPagerNav {
	display: flex;
	justify-content: space-between;
}
</style>
