<template>
	<div class="card-inner">
		<div class="card-image">
			<img v-if="background"
				:src="background"
				@load="isLoaded = true"
				alt="Splash" />
		</div>

		<div class="description">
			<slot name="card-header"></slot>
			<slot name="card-description">Click to read!</slot>
		</div>
	</div>
</template>

<script setup>
	import { ref } from 'vue'
	const isLoaded = ref(false)
	const props = defineProps({
		background: { type: String },
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
		background: linear-gradient(135deg,
			var(--primary-accent-color) 0% 33%,
			var(--secondary-accent-color) 33%,
			var(--ternary-accent-color)
		);
	}

	.card-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		display: block;
	}

	.description {
		background-color: var(--secondary-body-color);
	}
</style>
