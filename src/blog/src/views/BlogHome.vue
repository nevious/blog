<template>
	<div class="blog-home">
		<h1>My blog</h1>
		<ul class="post-list">
			<li v-for="post in posts" :key="post.slug">
				<router-link :to="`/posts/${post.slug}`">{{ post.title }}</router-link>
			</li>
		</ul>
	</div>
</template>

<script setup>
	import { onMounted, computed } from 'vue'
	import { useStore } from 'vuex'

	// Access the store
	const store = useStore()

	// Fetch all posts on mount
	onMounted(() => {
	  store.dispatch('fetchPosts')
	})

	const posts = computed(() => store.state.posts)
</script>

<style>
	.post-list {
		list-style: none;
		padding: 0;
	}

	.post-list li {
		margin-bottom: 20px;
	}

	.date {
		font-size: 0.8rem;
		color: #666;
	}
</style>
