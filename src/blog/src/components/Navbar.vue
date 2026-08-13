<template>
	<nav>
		<router-link to="/">
			<Button title="Home" />
		</router-link>

		<!-- <router-link to="/">
			<Button title="Categories" />
		</router-link> -->

		<div class="grow">
			<SearchBar />
		</div>

		<router-link to="/about">
			<Button title="About" />
		</router-link>

		<span class="pad" v-if="theme.activeTheme.value === 'light'" @click="theme.toggleTheme()">💡</span>
		<span class="pad" v-else @click="theme.toggleTheme()">🔦</span>
	</nav>
</template>

<script setup>
	import Button from './Button.vue'
	import SearchBar from './SearchBar.vue'
	import { inject } from 'vue'
	const theme = inject('theme')
</script>

<style scoped>
	nav {
		display: flex;
		flex-grow: 1;
		justify-content: space-evenly;
		align-items: center;
		padding: 0.5rem;
	}

	nav a:link,
	nav a:visited,
	nav a:hover,
	nav a:active {
		text-decoration: none;
		color: inherit;
	}

	.grow {
		flex-grow: 1;
	}

	.pad {
		padding-left: 0.25rem;
		padding-right: 0.25rem;
	}
</style>

