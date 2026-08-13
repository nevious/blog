<script setup>
	import { ref, provide, watchEffect } from 'vue'
	import Header from '@/components/Header.vue'
	import Footer from '@/components/Footer.vue'
	const activeTheme = ref(localStorage.getItem('theme') || 'light')

	provide('theme', {activeTheme, toggleTheme})

	function toggleTheme() {
		activeTheme.value = activeTheme.value == 'light' ? 'dark' : 'light'
	}

	watchEffect(() => {
		document.documentElement.className = activeTheme.value
		localStorage.setItem('theme', activeTheme.value)
	})
</script>

<template>
	<Header />
	<router-view />
	<Footer />
</template>

<style scoped>
</style>
