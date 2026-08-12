<template>
	<div v-if="post" class="blog-post">
		<h1 class="post-title">{{ post.meta.title }}</h1>

		<div class="frontmatter">
			<span v-if="post.meta.date"><b>On</b> {{ dateFormatter(post.meta.date) }}</span>
			<span v-if="post.meta.author"><b>by</b> {{ post.meta.author }}</span>
			<span v-if="post.meta.category"><b>in</b></span>
			<div class="category-span">
				<span v-for="category in post.meta.category" class="category-tag">{{category}}</span>
			</div>
		</div>

		<MarkdownRenderer :content="post.content" />
		<PostPager />
	</div>

	<div v-else class="blog-post">
		<p>Wrong turn at Albuquerque... mayb go <router-link to="/">back...</router-link></p>
	</div>

</template>

<script setup>
	import { onMounted, watch, computed } from 'vue'
	import { usePostStore } from '@/stores/glogPost'
	import { useRoute } from 'vue-router'
	import MarkdownRenderer from '../components/MarkdownRenderer.vue'
	import PostPager from '../components/PostPager.vue'

	// Get store and route
	const postStore = usePostStore()
	const route = useRoute()

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
		postStore.loadPostBySlug(route.params.slug)
	})

	// Re-fetch if the route changes
	watch(
		() => route.params.slug,
		(newSlug, oldSlug) => {
			postStore.loadPostBySlug(newSlug)
		}
	)

	// Reactive reference to current post
	const post = computed(() => postStore.currentPost)
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
		color: var(--primary-accent-color);
		margin: 0 0 0.5rem 0;
		line-height: 1.2;
	}

	.frontmatter {
		display: flex;
		gap: 1rem;
		align-items: center;
		flex-wrap: wrap;
		font-size: var(--text-sm);
		color: var(--muted-font-color);
		margin-bottom: 2rem;
		padding-bottom: 0.75rem;
		border-bottom: 1px solid var(--primary-accent-color-25);
	}

	.category-span {
		display: flex;
		gap: 0.5rem;
	}

	.category-tag {
		background: var(--secondary-accent-color-25);
		color: var(--primary-accent-color);
		padding: 0.15rem 0.6rem;
		border-radius: 999px;
		font-size: var(--text-sm);
	}

	:deep(.markdown-rendered a) {
		text-decoration: none;
		color: var(--secondary-accent-color-75);
	}

	:deep(.markdown-rendered a:hover) {
		text-decoration: none;
		color: var(--ternary-accent-color-75);
	}

	:deep(.markdown-rendered img) {
		width: clamp(250px, 100%, 900px);
		height: auto;
	}

	:deep(.markdown-rendered li) {
		list-style-type: "🔻 ";
		font-family: "Noto Emoji", "Noto Sans", sans-serif
	}
</style>
