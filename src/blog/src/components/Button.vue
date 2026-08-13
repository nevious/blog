<template>
	<div class="button">
        <div class="title">{{ title }}</div>
        <div class="description">{{ description}}</div>
	</div>
</template>

<script setup>
	const props = defineProps({
		title: {type: String},
		description: {type: String}
		}
	)
</script>


<style scoped>
.title {
	font-size: var(--button-title-size);
}

.description {
	font-size: var(--button-description-size);
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
