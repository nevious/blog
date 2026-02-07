<template>
  <div v-if="post" class="blog-post">
    <h2>{{ post.meta.title }}</h2>
	<div class="frontmatter">
		<p v-if="post.meta.date">on: {{ dateFormatter(post.meta.date) }}</p>
		<p v-if="post.meta.author">by: {{ post.meta.author }}</p>
		<p v-if="post.meta.category">in: {{ post.meta.category }}</p>
	</div>

    <MarkdownRenderer :content="post.content" />

	<PostPager />
  </div>

  <div v-else>
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
	const d = new Date()
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
.frontmatter {
	display: flex;
	justify-content: space-between;
	font-size: 0.8rem;
	flex-shrink: 1;
	color: #666;
}

.blog-post {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  width: 100vw;        /* take full viewport width */
  flex-basis: 1;
  margin: 0 auto;
  padding: 1rem;
  max-width: 900px;
  margin: 0 auto;
  flex-grow: 1;
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
