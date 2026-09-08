<template>
	<div class="container">
		<MarkdownRenderer :content=aboutContent />
	</div>
</template>

<script setup>
	import aboutContent from '@/assets/About.md?raw'
	import MarkdownRenderer from '@/components/MarkdownRenderer.vue';
</script>

<style scoped>
.container {
	display: flex;
	flex-direction: column;
	align-items: center;
	width: 100vw;
	flex-basis: 1;
	margin: 0 auto;
	box-sizing: border-box;
	padding: 1rem;
	max-width: 900px;
	margin: 0 auto;
	flex-grow: 1;
}
</style>
