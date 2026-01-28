<template>
  <div v-if="post" class="blog-post">
    <h1>{{ post.title }}</h1>
    <p class="date" v-if="post.date">{{ post.date }}</p>
    <MarkdownRenderer :content="post.content" />

	<PostPager />
  </div>

  <div v-else>
    <p>
		Wrong turn at Albuquerque...
		Maybe go <router-link to="/">back...</router-link>
    </p>
  </div>

</template>

<script setup>
import { onMounted, watch, computed } from 'vue'
import { usePostStore } from '@/stores/posts'
import { useRoute } from 'vue-router'
import MarkdownRenderer from '../components/MarkdownRenderer.vue'
import PostPager from '../components/PostPager.vue'

// Get store and route
const postStore = usePostStore()
const route = useRoute()

// Fetch post on mount
// onMounted executed when BlogPost is mounted somewhere
onMounted(async () => {
	if (postStore.posts.length == 0) {
		await postStore.fetchPosts()
	}
	postStore.fetchPostBySlug(route.params.slug)
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
const post = computed(() => postStore.currentPost)
const posts = computed(() => postStore.posts )
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
