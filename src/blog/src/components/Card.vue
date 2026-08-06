<template>
	<div class="card-inner" :class="{ featured }">
		<div class="card-image">
			<img v-if="background" :src="imageSrc" alt="" />
			<div v-else class="card-image-fallback" />
		</div>
		<div class="description">
			<h3>{{ title }}</h3>
			<p>{{ description || 'Some description would do this post some good!' }}</p>
		</div>
	</div>
</template>

<script setup>
	import { computed } from 'vue'

	const props = defineProps({
		title: { type: String },
		description: { type: String },
		background: { type: String },
		featured: { type: Boolean, default: false }
	})

	const imageSrc = computed(() => {
		if (!props.background) return null
		return props.background.replace(/^url\((['"]?)/, '').replace(/(['"]?)\)$/, '')
	})
</script>

<style scoped>
	.card-inner {
		display: grid;
		grid-template-rows: 4fr 1fr;
		height: 100%;
	}

	.card-image {
		overflow: hidden;
	}

	.card-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		display: block;
	}

	.card-image-fallback {
		width: 100%;
		height: 100%;
		background: linear-gradient(135deg, var(--primary-accent-color) 0% 33%, var(--secondary-accent-color) 33%, var(--ternary-accent-color));
	}

	.card-inner.featured .card-image {
		clip-path: none;
	}

	.description {
		background: white;
	}

	.description h3 {
		margin: 0.75rem 1rem 0.25rem 1rem;
		font-size: var(--text-lg);
		color: var(--primary-accent-color);
	}

	.description p {
		margin: 0;
		padding: 0 1rem 0.75rem;
		font-size: var(--text-sm);
		color: oklch(0.35 0.02 229);
		line-height: 1.5;
	}
</style>
