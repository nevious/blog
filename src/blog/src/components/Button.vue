<template>
	<div class="button">
		<div class="title" v-html="title"></div>
		<div class="description" v-html="description"></div>
	</div>
</template>

<script setup>
	import { computed } from 'vue'

	const props = defineProps({
		title: {type: String},
		description: {type: String}
		}
	)

	const title = computed(() => props.title)
	const description = computed(() => props.description)
</script>

<style scoped>
.title {
	color: var(--primary-font-color);
	font-size: var(--button-title-size);
}

.description {
	font-size: var(--button-description-size);
	color: var(--primary-font-color);
}

.button {
	background-color: var(--secondary-accent-color);
	border-radius: var(--border-radius);
	padding: 0.3rem 0.6rem 0.3rem 0.6rem;
	margin: 0 0.5rem 0 0.5rem;
}

.button:hover {
	background-color: var(--ternary-accent-color);
}
</style>
