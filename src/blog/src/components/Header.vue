<template>
	<div class="pageNav">
		<div class="logo">
			<div class="logo-svg">
				<img :src="siteStore.logo" :alt="siteStore.description" />
			</div>
			<h1>{{ siteStore.siteTitle }}</h1>
		</div>
		<Navbar />
	</div>
</template>

<script setup>
	import Navbar from '@/components/Navbar.vue'
	import { useSiteStore } from '@/stores/site'
	const siteStore = useSiteStore()
</script>

<style scoped>
	.pageNav {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		padding: 0.5rem 1rem;
		background-color: var(--primary-accent-color);
		color: var(--logo-color)
	}

	.logo {
		display: flex;
		align-items: center;
		gap: 0.75rem;
	}

	.logo-svg {
		width: 78px;
		height: 52px;
		overflow: hidden;
		flex-shrink: 0;
		border-radius: 4px;
	}

	.logo-svg img {
		width: 100%;
		height: 100%;
		object-fit: contain;
		display: block;
	}

	.logo h1 {
		margin: 0;
	}
</style>
