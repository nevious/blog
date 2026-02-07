<template>
	<div class="header">
		<div class="logo">
			<h1>{{ pageSettings.title }}</h1>
		</div>
		<Navbar />
	</div>
</template>

<script setup>
	import Navbar from '@/components/Navbar.vue'
	import { config } from '@/config/config'
	import { computed } from 'vue'
	const pageSettings = computed(() => config.site )
</script>

<style scoped>
.header {
	display: flex;
	flex-wrap: wrap;
	padding: 0 0.4rem 0 0.4rem;
	background-color: var(--primary-accent-color);
	color: var(--primary-font-color);
}

.logo {
	margin: auto;
}

.description {
	text-align: right;
	font-size: x-small;
	margin: 0;
	margin-right: 0.5rem;
}
</style>
