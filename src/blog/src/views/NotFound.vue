<template>
	<div class="container">
		<h1>Not found</h1>
		<p>Go <router-link to="/">back</router-link></p>
	</div>
</template>

<script setup>
</script>

<style scoped>
	.container {
		display: flex;
		flex-direction: column;
		align-items: center;
		width: 100vw;
		flex-basis: 1;
		margin: 0 auto;
		box-sizing: border-box;
		padding: 1rem;
		max-width: 900px;
		margin: 0 auto;
		flex-grow: 1;
	}

	.container a {
		color: var(--ternary-accent-color);
	}
</style>
