<template>
	<div ref="container" class="markdown-rendered" v-html="renderedContent"></div>
</template>

<script setup>
	import { computed, ref, watch, nextTick } from 'vue'
	import { marked } from 'marked'
	import hljs from 'highlight.js'
	import 'highlight.js/styles/atom-one-dark.css'

	const props = defineProps({ content: { type: String, default: '' } })
	const container = ref(null)
	const renderedContent = computed(() => marked.parse(props.content))

	watch(renderedContent, async () => {
		await nextTick()
		container.value.querySelectorAll('pre code').forEach(block => hljs.highlightElement(block))
		// understanding:
		// passing { immediate: true} forces the watcher to "fire" essentially like "onMounted()"
		// replacing this watcher with onMounted however doesn't work as the component HAS been mounted
		// but the v-html portion might still be loading
		container.value.querySelectorAll('img').forEach( image => {
			image.style.filter = 'blur(10px)'
			image.style.opacity = '0'
			image.style.transition = 'filter 0.3s ease, opacity 0.3s ease'
			image.loading = 'lazy'

			image.onload = () => {
				image.style.filter = 'blur(0)'
				image.style.opacity = '1'
			}
		})
	}, { immediate: true })
</script>

<style scoped>
:deep(blockquote) {
	background-color: var(--secondary-accent-color-25);
	padding: 1rem;
	border-left: 4px solid var(--primary-accent-color);
}
</style>
