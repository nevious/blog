<template>
	<div class="search">
		<input
			ref="searchInput"
			type="search"
			placeholder="Search... Ctrl+k"
		/>
	</div>
</template>

<style scoped>
.search {
  align-self: stretch;
  padding: 0.5rem;
}

.search input {
  width: 100%;
  box-sizing: border-box;

  padding: 0.6rem 1rem;
  border-radius: 9999px;

  /* Default: slightly lighter than the primary background */
  background-color: rgba(255, 255, 255, 0.1); /* subtle contrast */
  border: 1px solid rgba(0, 0, 0, 0.08);

  /* font-size: 1rem; */
  line-height: 1.2;

  outline: none;
  transition: 
    background-color 0.2s ease,
    border-color 0.2s ease;
}

/* Hover: slightly stronger blending */
.search input:hover {
  background-color: rgba(255, 255, 255, 0.15);
  border-color: rgba(0, 0, 0, 0.12);
}

/* Focus: fully primary background */
.search input:focus {
  background-color: var(--primary-body-color);
  border-color: rgba(0, 0, 0, 0.35);
}

.search input::placeholder {
  opacity: 0.6;
}
</style>

<script setup>
	import { ref, onMounted, onBeforeUnmount } from 'vue'

	const searchInput = ref(null)

	function handleKeyEvent(event) {
		if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === "k") {
			event.preventDefault()
			searchInput.value.focus()
		}
	}

	onMounted(() => {
	  window.addEventListener('keydown', handleKeyEvent)
	})

	onBeforeUnmount(() => {
	  window.removeEventListener('keydown', handleKeyEvent)
	})
</script>
