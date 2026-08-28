<template>
	<div v-if="postStore.isLoading" class="blog-container">
		<Loader class="fallback-block" />
	</div>

	<div v-else-if="postStore.error" class="blog-post">
		<Error title='Oops!' :message="postStore.error.message" />
	</div>

	<div v-else-if="selectedPosts?.length > 0" class="blog-container">
		<FilterBar :tags="tagSet" @filter="filterCallback"/>

		<router-link v-for="(post, index) in selectedPosts"
			class="blog-item"
			:class="{ feature: index == 0}"
			:key="post.slug"
			:to="`/posts/${post.slug}`">

			<Card :background="post.splash ? post.splash : undefined" >
				<template #card-header>
					<h3 class="card-title">{{ post.title }}</h3>
				</template>

				<template #card-description>
					<p class="card-description">{{ post.description || 'Have a read' }}</p>
				</template>
			</Card>

		</router-link>
	</div>
</template>

<script setup>
	import { onMounted, computed, ref } from 'vue'
	import { usePostStore } from '@/stores/glogPost'
	import Card from '@/components/Card.vue'
	import Error from '@/components/Error.vue'
	import Loader from '@/components/Loader.vue'
	import FilterBar from '@/components/FilterBar.vue'
 
	const postStore = usePostStore()
	const activeTags = ref([])

	onMounted(async () => {
		await postStore.loadPosts()
	})

	const tagSet = computed(() => {
		const allTags = postStore.posts.flatMap(post => post.category || [])
		return [...new Set(allTags)]
	})

	const selectedPosts = computed(() => {
		if (!activeTags.value || activeTags.value.length === 0) {
			return postStore.posts
		}

		return postStore.posts.filter(post => {
			const tags = post.category || []
			return tags.some(cat => activeTags.value.includes(cat))
		})
	})

	function filterCallback(filterList) {
		activeTags.value = filterList
	}
</script>

<style scoped>
	.blog-container {
		flex-grow: 1;
		width: clamp(180px, 100%, 1100px);
		margin: 0 auto;
		display: flex;
		flex-wrap: wrap;
		gap: 1.5rem;
		padding: 2rem 1.5rem;
		justify-content: center;
		align-content: start;
		box-sizing: border-box;
	}

	.blog-item {
		flex: 1 1 calc(33.333% - 2rem);
		flex-wrap: wrap;
		box-sizing: border-box;
		box-shadow: 0 0 0 0px var(--primary-accent-color);
		max-height: 250px;
		transition: transform 0.2s;
		overflow: hidden;
	}

	.blog-item:not(.feature) {
		flex: 1 1 calc((100% - 2rem*2)/3);
		min-width: clamp(200px, calc((100% - 2rem*2)/3), 500px);
	}

	.blog-item:hover {
		transform: scale(1.02) ;
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
		height: 500px;
		max-height: 500px;
		box-sizing: border-box;
	}

	.card-title {
		margin: 0.75rem 1rem 0.25rem 1rem;
		font-size: var(--text-lg);
		color: var(--primary-font-color);
	}

	.card-description {
		margin: 0;
		padding: 0 1rem 0.75rem;
		font-size: var(--text-sm);
		color: var(--primary-font-color);
	}
</style>
