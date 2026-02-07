<template>
	<div class="blog-container">
		<router-link v-if="featuredPost" class="blog-item feature" :to="featuredPost.slug">
			<div class="card-title"><h3>{{ featuredPost.title }}</h3></div>
			<div class="card-description">{{ featuredPost.description || "Some featured description text"}}</div>
		</router-link>

		<router-link class="blog-item" v-for="post in posts" :key="post.slug" :to="`/posts/${post.slug}`">{{ post.title }}
			<div class="card-title"><h3>{{ post.title }}</h3></div>
			<div class="card-description">{{ post.description || "Some descriptive test" }}</div>
		</router-link>
	</div>
</template>

<script setup>
	import { onMounted, computed } from 'vue'
	import { usePostStore } from '@/stores/glogPost'

	const postStore = usePostStore()
	onMounted(async () => {
		await postStore.loadPosts()
	})

	const featuredPost = computed(() => postStore.posts[0])
	const posts = computed(() => postStore.posts.slice(1))
	console.log("featured: %o", featuredPost)
	console.log("posts: %o", posts)
</script>

<style scoped>

.post-list {
	list-style: none;
	padding: 0;
}

.post-list li {
	margin-bottom: 20px;
}

.blog-container {
	flex-grow: 1;
	max-width: 900px;
	margin: 0 auto;
	display: flex;
	flex-wrap: wrap;
	gap: 2rem;
	padding: 2rem;
}

.blog-item {
	display: block;
	flex: 1 1 calc(33.333% - 2rem);
	box-sizing: border-box;
	/*border: 1px solid var(--primary-accent-color);*/
	box-shadow: 0 0 0 1.2px var(--primary-accent-color);
	border-radius: 8px;
	padding: 1rem;
	transition: transform 0.2s;
}

.blog-item:hover {
	transform: rotate(1deg) scale(1.03) ;
}

.blog-item a,
.blog-item a:link,
.blog-item a:visited,
.blog-item a:hover,
.blog-item a:active {
	text-decoration: none;
	color: inherit;
}

.feature {
	flex: 0 0 100%;
}
</style>
