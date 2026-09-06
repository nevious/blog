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

		// Safety rail in case container has not yet been populated
		// Due to immediate: true, the below code may run before the
		// content is available
		if (!container.value) return

		container.value.querySelectorAll('pre code').forEach(block => hljs.highlightElement(block))

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
	.markdown-rendered {
		font-size: var(--text-base);
		line-height: 1.75;
	}

	:deep(blockquote) {
		background-color: var(--secondary-accent-color-25);
		padding: 1rem;
		border-left: 4px solid var(--primary-accent-color);
	}

	:deep(.markdown-rendered a) {
		text-decoration: none;
		color: var(--ternary-accent-color);
	}

	:deep(.markdown-rendered a:hover) {
		text-decoration: none;
		color: var(--ternary-accent-color-50);
	}

	:deep(.markdown-rendered img) {
		width: clamp(250px, 100%, 900px);
		height: auto;
	}

	:deep(.markdown-rendered li) {
		list-style-type: "🔻 ";
	}

	:deep(.markdown-rendered ul li::marker) {
		 font-family: "Noto Emoji", sans-serif;
	 }

	:deep(h2, h3) {
		clear: both;
		padding-top: 1.5rem;
	}
</style>
