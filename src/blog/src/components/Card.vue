<template>
	<div class="card-inner" :style="{'--background': background}">
		<div class="heading" />
		<div class="description">
			<h3 v-html="title" />
			<p v-html="description || 'Some description would do this post some good!'"/>
		</div>
	</div>
</template>

<script setup>
	import { computed } from 'vue'

	const props = defineProps({
		title: {type: String},
		description: {type: String},
		background: {type: String}
	})
</script>

<style scoped>
.card-inner {
  display: grid;
  grid-template-rows: 1.5fr 1fr;
  height: 100%;
}

.heading {
  flex: 0 0 33%; /* top third */
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: var(--background, linear-gradient(to right, var(--default-splash-color), var(--secondary-accent-color)));
  box-shadow: inset 0 0 7px rgba(0, 0, 0, 0.2);
  background-size: cover;     /* scales the image to fully cover the div */
  background-position: center; /* centers the image */
  background-repeat: no-repeat; /* prevents tiling */
}

.description h3 {
	margin: 1rem 1rem 0.2rem 1rem;
}

.description p {
  padding: 0rem 1rem;
  font-size: small;
}
</style>
