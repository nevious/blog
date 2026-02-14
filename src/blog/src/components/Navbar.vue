<template>
	<nav>
		<router-link to="/">
			<Button title="Home" />
		</router-link>

		<!-- <router-link to="/">
			<Button title="Categories" />
		</router-link> -->

		<router-link class="grow" to="">
			<SearchBar />
		</router-link>

		<router-link to="/about">
			<Button title="About me" />
		</router-link>
	</nav>
</template>

<script setup>
	import Button from './Button.vue'
	import SearchBar from './SearchBar.vue'
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
</style>

