<template>
	<div class="blog-post">
		<h1>{{ post.title }}</h1>
		<p class="date">{{ post.date }}</p>
		<MarkdownRenderer :content="post.content"></MarkdownRenderer>
	</div>

	<div>
		<a href="/">Back</a>
	</div>
</template>

<script>
	import MarkdownRenderer from '../components/MarkdownRenderer.vue'
	import { mapState } from 'vuex'

	export default {
		components: { MarkdownRenderer },
		computed: {
			...mapState(['currentPost']),
			post() {
				return this.currentPost
			}
		},
		created() {
			this.$store.dispatch('fetchPostBySlug', this.$route.params.slug)
		}
	}
</script>


