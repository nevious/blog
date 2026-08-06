<template>
	<footer class="footer">
		<div class="footer-inner">
			<span class="footer-copy">© {{ year }} — N E V I</span>
			<nav class="footer-nav">
				<router-link to="/">Home</router-link>
				<router-link to="/about">About</router-link>
			</nav>
		</div>
	</footer>
</template>

<script setup>
	const year = new Date().getFullYear()
</script>

<style scoped>
.footer {
	width: 100%;
	background-color: var(--primary-accent-color);
	color: var(--primary-font-color);
}

.footer-inner {
	display: flex;
	align-items: center;
	justify-content: space-between;
	max-width: 1100px;
	margin: 0 auto;
	padding: 1rem 1.5rem;
}

.footer-copy {
	font-size: var(--text-sm);
	opacity: 0.85;
	letter-spacing: 0.04em;
}

.footer-nav {
	display: flex;
	gap: 1.5rem;
}

.footer-nav a {
	font-size: var(--text-sm);
	color: var(--primary-font-color);
	text-decoration: none;
	opacity: 0.85;
	transition: opacity 0.2s;
}

.footer-nav a:hover {
	opacity: 1;
}
</style>
