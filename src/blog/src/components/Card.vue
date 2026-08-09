<template>
	<div class="card-inner">
		<div class="card-image">
			<img v-if="background" :src="background" alt="" />
			<div v-else class="card-image-fallback" />
		</div>

		<div class="description">
			<slot name="card-header"></slot>
			<slot name="card-description">Click to read!</slot>
		</div>
	</div>
</template>

<script setup>
	const props = defineProps({
		background: { type: String },
		featured: { type: Boolean, default: false }
	})
</script>

<style scoped>
	.card-inner {
		display: grid;
		grid-template-rows: 4fr 0.75fr;
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
		background: linear-gradient(135deg,
			var(--primary-accent-color) 0% 33%,
			var(--secondary-accent-color) 33%,
			var(--ternary-accent-color)
		);
	}

	.card-inner.featured .card-image {
		clip-path: none;
	}

	.description {
		background-color: var(--secondary-body-color);
	}
</style>
