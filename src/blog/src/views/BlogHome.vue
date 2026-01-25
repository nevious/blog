<template>
	<div class="blog-home">
		<h1>My blog</h1>
		<ul class="post-list">
			<li v-for="post in posts" :key="post.slug">
				<router-link :to="{ name: 'post', params: {slug: post.slug } }">
					{{ post.slug }}
				</router-link>
				<p class="date">{{ post.date }}</p>
			</li>
		</ul>
	</div>
</template>

<script>
	import { mapState } from 'vuex'

	export default {
		computed: {
			...mapState(['posts'])
		},
		created() {
			this.$store.dispatch('fetchPost')
		}
	}
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
