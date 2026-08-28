<template>
	<nav>
		<router-link to="/">
			<Button title="Home" />
		</router-link>

		<div class="grow">
			<SearchBar />
		</div>

		<router-link to="/about">
			<Button title="About" />
		</router-link>

		<span class="toggle" v-if="activeTheme === 'light'" @click="toggleTheme"><Sun size="20" /></span>
		<span class="toggle" v-else @click="toggleTheme"><Moon size="20" /></span>
	</nav>
</template>

<script setup>
	import Button from './Button.vue'
	import SearchBar from './SearchBar.vue'
	import { inject } from 'vue'
	import { Sun, Moon } from '@lucide/vue'
	const {activeTheme, toggleTheme} = inject('theme')
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

	.toggle {
		cursor: pointer;
		padding-left: 0.25rem;
		padding-right: 0.25rem;
	}
</style>

