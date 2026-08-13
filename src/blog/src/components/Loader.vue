<script setup>
	import { ref } from 'vue'
	const phrases = [
		"We'll be right with ya!",
		"Hol'on a sec, please",
		"Almost there...",
		"Nothing to it but to do it!",
		"Please hold."
	]
	const message = ref(phrases[Math.floor(Math.random() * phrases.length)])
</script>

<template>
	<h1>{{ message }}</h1>
</template>

<style scoped>
</style>
