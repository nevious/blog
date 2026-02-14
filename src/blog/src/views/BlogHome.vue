<template>
	<div v-if="posts?.length > 0" class="blog-container">
		<router-link v-if="featuredPost" class="blog-item feature" :to="`/posts/${featuredPost.slug}`">
			<Card :title="featuredPost.title"
				:description="featuredPost.description"
				:background="featuredPost.splash ? `url(${featuredPost.splash})` : undefined" />
		</router-link>

		<router-link class="blog-item" v-for="post in posts" :key="post.slug" :to="`/posts/${post.slug}`">
			<Card :title="post.title"
				:description="post.description"
				:background="post.splash ? `url(${post.splash})` : undefined" />
		</router-link>
	</div>

	<div v-else class="blog-container">
		<div class="fallback">
			<h1>We'll see you soon....</h1>
			<p>Nothing to see here yet.</p>
		</div>
	</div>
</template>

<script setup>
	import { onMounted, computed } from 'vue'
	import Card from '@/components/Card.vue'
	import { usePostStore } from '@/stores/glogPost'

	const postStore = usePostStore()

	onMounted(async () => {
		await postStore.loadPosts()
	})

	const featuredPost = computed(() => postStore.posts[0])
	const posts = computed(() => postStore.posts.slice(1))

	console.log("Posts: %o", posts)
</script>

<style scoped>

.blog-container {
	flex-grow: 1;
	max-width: 900px;
	margin: 0 auto;
	display: flex;
	flex-wrap: wrap;
	gap: 2rem;
	padding: 2rem;
	justify-content: center;
}

.blog-item {
	flex: 1 1 calc(33.333% - 2rem);
	flex-wrap: wrap;
	box-sizing: border-box;
	box-shadow: 0 0 0 1.2px var(--primary-accent-color);
	border-radius: var(--border-radius);
	max-height: 250px;
	transition: transform 0.2s;
	overflow: hidden;
}

.blog-item:not(.feature) {
	flex: 1 1 calc((100% - 2rem*2)/3);
	max-width: calc((100% - 2rem*2)/3);
	min-width: 250px;
}

.blog-item:hover {
	transform: rotate(1deg) scale(1.02) ;
}

a,
a:link,
a:visited,
a:hover,
a:active {
	text-decoration: none;
	color: inherit;
}

.feature {
	flex: 0 0 100%;
	height: 350px;
	max-height: 350px;
}

.fallback {
	margin: auto;
}
</style>
