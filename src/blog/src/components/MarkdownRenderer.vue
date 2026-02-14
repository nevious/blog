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
})
</script>

<template>
  <div ref="container" class="markdown-rendered" v-html="renderedContent"></div>
</template>

<style scoped>
:deep(blockquote) {
  background-color: var(--secondary-accent-color-25);
  padding: 1rem;
  border-left: 4px solid var(--primary-accent-color);
}
</style>
