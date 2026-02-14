<template>
	<div class="pageNav">
		<h1>{{ pageSettings.title }}</h1>
		<Navbar />
	</div>

	<div class="containHeader" :style="{'--background': `url(${pageSettings.image})`}">
		<!-- Optional: hero text below nav -->
		<div v-if="pageSettings.description" class="heroContent">
		  <p>{{ pageSettings.description }}</p>
		</div>
  </div>
</template>

<script setup>
	import Navbar from '@/components/Navbar.vue'
	import { config } from '@/config/config'
	import { computed } from 'vue'
	const pageSettings = computed(() => config.site )
	console.log("config: %o", pageSettings)
</script>

<style scoped>
.containHeader {
	display: flex;
	align-content: flex-end;
	justify-content: flex-end;
	align-items: flex-end;
	height: 450px;
	background-image: var(--background);
	background-size: cover;
	background-position: center;
	background-repeat: no-repeat;
}

.pageNav {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	color: var(--primary-font-color);
	background-color: var(--primary-accent-color);
}

.heroContent {
	padding: 1rem;
	color: var(--primary-font-color);
	font-weight: small;
}

</style>
