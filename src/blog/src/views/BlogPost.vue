<template>
  <div v-if="post" class="blog-post">
    <h1>{{ post.title }}</h1>
    <p class="date" v-if="post.date">{{ post.date }}</p>
    <MarkdownRenderer :content="post.content" />

    <div style="margin-top: 20px;">
      <router-link to="/">Back</router-link>
    </div>
  </div>

  <div v-else>
    <p>Loading post...</p>
  </div>

  <div v-if="posts" class="navigation">
	<li v-for="post in posts" :key="post.slug">
		<router-link :to="`/posts/${post.slug}`">{{ post.title }}</router-link>
	</li>
  </div>
</template>

<script setup>
import { onMounted, watch, computed } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import MarkdownRenderer from '../components/MarkdownRenderer.vue'

// Get store and route
const store = useStore()
const route = useRoute()

// Function to fetch post by slug
const fetchPost = () => {
  console.log('Dispatching fetchPostBySlug with', route.params.slug)
  store.dispatch('fetchPostBySlug', route.params.slug)
}

// Fetch post on mount
onMounted(async () => {
	if (store.state.posts.length == 0) {
		console.log('posts are empty, fetching')
		await store.dispatch('fetchPosts')
	}
	store.dispatch('fetchPostBySlug', route.params.slug)
})

// Re-fetch if the route changes
//watch(() => route.params.slug, fetchPost)
watch(
	() => route.params.slug,
	(oldSlug, newSlug) => {
		console.log('watch() called from: ', oldSlug, ' to ', newSlug)
		fetchPost()
	}
)

// Reactive reference to current post
const post = computed(() => store.state.currentPost)
const posts = computed(() => store.state.posts )
</script>

<style scoped>
.blog-post {
  max-width: 800px;
  margin: 0 auto;
}

.markdown-rendered {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
}

.markdown-rendered h1,
.markdown-rendered h2,
.markdown-rendered h3 {
  color: #333;
}

.markdown-rendered code {
  background-color: #f4f4f4;
  padding: 2px 4px;
  border-radius: 4px;
}
</style>
