<script setup>
	const props = defineProps({
		title: { type: String},
		message: { type: String },
		display: { type: Boolean, default: true}
	})
</script>

<template>
	<div v-if="display" class="error">
		<h3>{{ title }}</h3>
		<p>{{ message }}</p>
	</div>
</template>

<style scoped>
	.error {
		margin-left: auto;
		margin-right: auto;
	}
</style>
