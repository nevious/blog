<template>
	<div ref="container" class="markdown-rendered" v-html="renderedContent"></div>
</template>

<script setup>
	import { computed, ref, watch, nextTick } from 'vue'
	// NOTE:
	// import { thing } --> named import
	// import thing --> default import
	import  marked from '@/plugins/marked'
	import hljs from 'highlight.js'
	import 'highlight.js/styles/atom-one-dark.css'

	const props = defineProps({ content: { type: String, default: '' } })
	const emit = defineEmits(['image-click'])
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

			if (image.alt && !image.title) image.title = image.alt;

			image.onload = () => {
				image.style.filter = 'blur(0)'
				image.style.opacity = '1'
			}

			image.onclick = () => {
				emit('image-click', image)
			}

			// Trigger the reset if the image is already loaded and somehow it was missed
			if (image.complete) {
				image.onload()
			}
		})
	}, { immediate: true })


</script>

<style scoped>
	:deep(blockquote) {
		background-color: var(--primary-accent-color-50);
		padding: 1rem;
		border-left: 4px solid var(--primary-accent-color);
	}

	:deep(img){
		cursor: zoom-in;
		width: clamp(250px, 100%, 900px);
		height: auto;
	}

	:deep(.gallery) {
		columns: 3 200px;
		column-gap: 0.125rem;
	}

	:deep(.gallery img) {
		width: 100%;
		height: auto;
		margin-bottom: 0.125rem;
		display: block;
	}

	:deep(.no-zoom) {
		cursor: default;
	}
</style>
