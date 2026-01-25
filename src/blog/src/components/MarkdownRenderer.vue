
<template>
	<div v-html="renderedContent"></div>
</template>

<script>
	import { marked } from 'marked'
	import hljs from 'highlight.js';  // wtf is highlight.js?
	import 'highlight.js/styles/vs2015.css';  // yah sure, good stuff?

	export default {
		props: {
			content: {
				type: String,
				default: ''
			}
		},
		computed: {
			renderedContent() {
				const options = {
					highlight: (code, lang) => {
						return hljs.highlightAuto(code).value
					},
					sanitize: true
				}
			return marked.parse(this.content, options)
			}
		}
	}
</script>
