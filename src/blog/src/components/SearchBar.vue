<template>
	<div class="search">
		<input
			v-model="search"
			ref="searchInput"
			type="search"
			placeholder="Search... Ctrl+k"
		/>
		<div v-if="results?.length" class="result">
			<ul>
				<li v-for="result in results" :key="result.slug">
				<router-link :to="`/posts/${result.slug}`">
						<h4>{{ result.title }}</h4>
						<p>{{ result.description }}</p>
					<hr />
				</router-link>
				</li>
			</ul>
		</div>
	</div>
</template>

<script setup>
	import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
	import { usePostStore } from '@/stores/glogPost'
	import Card from '@/components/Card.vue'

	const postStore = usePostStore()
	const searchInput = ref(null)
	const search = ref('')
	let results = ref(null)

	function clickOutsideSearchBar(event) {
		if (!searchInput.value?.contains(event.target)) {
			results.value = null
			searchInput.value = null
		}
	}

	function handleKeyEvent(event) {
		if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === "k") {
			event.preventDefault()
			searchInput.value.focus()
		}
	}

	watch(search, async(newSearch, oldSearch) => {
		const query = newSearch.toLowerCase()

		results.value = postStore.posts.filter((post) => {
			return post.title.toLowerCase().includes(query) ||
				post.description.toLowerCase().includes(query)
		})
	})

	onMounted(() => {
	  window.addEventListener('keydown', handleKeyEvent)
	  window.addEventListener('click', clickOutsideSearchBar)
	})

	onBeforeUnmount(() => {
	  window.removeEventListener('keydown', handleKeyEvent)
	  window.removeEventListener('click', clickOutsideSearchBar)
	})
</script>

<style scoped>
	.result {
		position: absolute;
		top: 100%;
		left: 1px;
		right: 1px;
		z-index: 10;    /* ensure it's above other content */
		background: var(--primary-accent-color-50);
		border-radius: 0 0 var(--border-radius) var(--border-radius);
		margin-top: -1px;
		color: black;
		box-sizing: border-box;
		font-size: 1rem;
		opacity: 0.95;
	}

	.result ul {
		padding: 1rem;
	}

	.result li {
		padding: 0.5rem 0.5rem;
		border-radius: var(--border-radius);
		transition: background 0.2s ease;
	}

	.result li:hover {
		background: var(--primary-accent-color-95);
	}

	.result h4 {
		font-size: 1rem;
		font-weight: 600;
		margin: 0 0 0.25rem 0;
		color: var(--primary-body-color);
	}

	.result p {
		font-size: 0.875rem;
		margin: 0;
		color: var(--primary-body-color, #111);
		line-height: 1.3;
	}

	.result hr {
		border: none;
		border-bottom: 1px solid var(--primary-body-color);
		margin: 0.5rem 0;
	}

	.result a {
		text-decoration: none;
	}

	.search {
		margin-left: 1rem;
		margin-right: 1rem;
		position: relative;
		align-self: stretch;
		border-bottom: 1px solid  var(--primary-body-color);
		border-bottom-color: rgba(var(--primary-body-color-rgb, 255,255,255), 0.3);
	}

	.search input {
		width: 100%;
		border: none;
		background: transparent;
		font-size: 1rem;

		outline: none;
		transition: 
			background-color 0.2s ease,
			border-color 0.2s ease;
		padding: 0.5rem;
	}

	.search input:focus {
		border-color: rgba(0, 0, 0, 0.35);
		color: var(--primary-font-color)
	}

	.search input::placeholder {
		opacity: 0.8;
	}
</style>
