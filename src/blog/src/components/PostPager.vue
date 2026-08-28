<template>
	<nav class="postPagerNav">
		<router-link v-if="olderPost" :to="`/posts/${olderPost.slug}`" class="pager-link pager-prev">
			<span class="pager-label"><ArrowLeftFromLine /></span>
			<span class="pager-title">{{ olderPost.title }}</span>
		</router-link>
		<span v-else class="pager-placeholder" />

		<router-link v-if="newerPost" :to="`/posts/${newerPost.slug}`" class="pager-link pager-next">
			<span class="pager-label"><ArrowRightFromLine /></span>
			<span class="pager-title">{{ newerPost.title }}</span>
		</router-link>
		<span v-else class="pager-placeholder" />

	</nav>
</template>

<script setup>
	import { computed } from 'vue'
	import { usePostStore } from '@/stores/glogPost'
	import { ArrowLeftFromLine, ArrowRightFromLine } from '@lucide/vue'

	const postStore = usePostStore()

	// Post order from backend is newer to older
	// hence older posts are next
	const olderPost = computed(() => postStore.getNextPost())
	const newerPost = computed(() => postStore.getPreviousPost())
</script>

<style scoped>
.postPagerNav {
	margin-top: 2.5rem;
	padding-top: 1.5rem;
	display: flex;
	justify-content: space-between;
	border-top: 1px solid var(--ternary-accent-color-50);
	gap: 1rem;
}

.pager-link {
	display: flex;
	flex-direction: column;
	gap: 0.25rem;
	text-decoration: none;
	max-width: 45%;
	color: var(--primary-font-color);
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
