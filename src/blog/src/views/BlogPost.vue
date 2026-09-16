<template>
	<div v-if="postStore.isLoading" class="blog-post">
		<Loader class="fallback-block" />
	</div>

	<div v-else-if="postStore.error" class="blog-post">
		<Error title='Oops!' :message="postStore.error.message" />
	</div>

	<div v-else-if="postStore.currentPost" class="blog-post">
		<h1 class="post-title">{{ postStore.currentPost.meta.title }}</h1>

		<div class="frontmatter">
			<span v-if="postStore.currentPost.meta.date"><b>On</b> {{ dateFormatter(postStore.currentPost.meta.date) }}</span>
			<span v-if="postStore.currentPost.meta.author"><b>by</b> {{ postStore.currentPost.meta.author }}</span>
			<span v-if="postStore.currentPost.meta.tags"><b>in</b></span>
			<div class="tags-span">
				<span v-for="tag in postStore.currentPost.meta.tags" class="category-tag">{{tag}}</span>
			</div>
		</div>

		<MarkdownRenderer :content="postStore.currentPost.content" @image-click="url => selectedImage = url" />
		<PostPager />

		<div v-if="selectedImage" class="lightbox-overlay" @click="selectedImage = null">
			<img :src="selectedImage" class="lightbox-image" />
		</div>
	</div>
</template>

<script setup>
	import { onMounted, watch, computed, ref } from 'vue'
	import { usePostStore } from '@/stores/glogPost'
	import { useRoute } from 'vue-router'
	import MarkdownRenderer from '@/components/MarkdownRenderer.vue'
	import PostPager from '@/components/PostPager.vue'
	import Error from '@/components/Error.vue'
	import Loader from '@/components/Loader.vue'

	// Get store and route
	const postStore = usePostStore()
	const route = useRoute()

	const selectedImage = ref(null)

	// date formatter
	const dateFormatter = (dateString) => {
		const d = new Date(dateString)
		return d.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' })
	}

	// Making sure to load all posts if this component is mounted.
	onMounted(async () => {
		if (postStore.posts.length == 0) {
			await postStore.loadPosts()
		}
		await postStore.loadPostBySlug(route.params.slug)
	})

	// Re-fetch if the route changes
	watch(
		() => route.params.slug,
		async (newSlug, oldSlug) => {
			await postStore.loadPostBySlug(newSlug)
		}
	)
</script>

<style scoped>
	.blog-post {
		display: flex;
		flex-direction: column;
		align-items: stretch;
		width: 100%;
		max-width: 900px;
		margin: 0 auto;
		flex-grow: 1;
		box-sizing: border-box;
		padding: 2rem 1.5rem;
	}

	.post-title {
		font-size: var(--text-2xl);
		margin: 0 0 0.5rem 0;
		line-height: 1.2;
	}

	.frontmatter {
		display: flex;
		gap: 1rem;
		align-items: center;
		flex-wrap: wrap;
		font-size: var(--text-sm);
		color: var(--primary-font-color);
		margin-bottom: 2rem;
		padding-bottom: 0.75rem;
	}

	.tags-span {
		display: flex;
		gap: 0.5rem;
	}

	.category-tag {
		background: var(--secondary-accent-color-25);
		padding: 0.15rem 0.6rem;
		border-radius: 999px;
		font-size: var(--text-sm);
	}

	.lightbox-overlay {
		position: fixed;
		inset: 0;
		background: rgba(0,0,0,0.7);
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 1000;
		cursor: zoom-out;
		backdrop-filter: blur(5px);
	}

	.lightbox-image {
		max-width: 95vw;
		max-height: 90vh;
		object-fit: contain;
		box-shadow: 0 0 30px rgba(0,0,0,0.5);
	}
</style>
