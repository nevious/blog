<template>
	<nav class="postPagerNav">
		<router-link v-if="previousPost" :to="`/posts/${previousPost.slug}`" class="pager-link pager-prev">
			<span class="pager-label">← Previous</span>
			<span class="pager-title">{{ previousPost.title }}</span>
		</router-link>
		<span v-else class="pager-placeholder" />

		<router-link v-if="nextPost" :to="`/posts/${nextPost.slug}`" class="pager-link pager-next">
			<span class="pager-label">Next →</span>
			<span class="pager-title">{{ nextPost.title }}</span>
		</router-link>
		<span v-else class="pager-placeholder" />
	</nav>
</template>

<script setup>
	import { computed } from 'vue'
	import { usePostStore } from '@/stores/glogPost'

	const postStore = usePostStore()

	const nextPost = computed(() => postStore.getNextPost())
	const previousPost = computed(() => postStore.getPreviousPost())
</script>

<style scoped>
.postPagerNav {
	margin-top: 2.5rem;
	padding-top: 1.5rem;
	border-top: 1px solid var(--primary-accent-color-25);
	display: flex;
	justify-content: space-between;
	gap: 1rem;
}

.pager-link {
	display: flex;
	flex-direction: column;
	gap: 0.25rem;
	text-decoration: none;
	color: var(--primary-accent-color);
	max-width: 45%;
	transition: color 0.2s;
}

.pager-link:hover {
	color: var(--ternary-accent-color);
}

.pager-label {
	font-size: var(--text-sm);
	font-weight: 600;
	letter-spacing: 0.03em;
}

.pager-title {
	font-size: var(--text-sm);
	color: var(--muted-font-color);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.pager-next {
	text-align: right;
	margin-left: auto;
}

.pager-placeholder {
	flex: 1;
}
</style>
