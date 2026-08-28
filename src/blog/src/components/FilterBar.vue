<script setup>
	import { ref, inject } from 'vue'
	import { Tags, Eraser } from '@lucide/vue'
	const filterList = ref(new Set())

	const props = defineProps({
		tags: { type: Array }
	})

	const emit = defineEmits(["filter"])

	function handleClick(tag) {
		if (filterList.value.has(tag)) {
			filterList.value.delete(tag)
		} else {
			filterList.value.add(tag)
		}
		emit('filter', [...filterList.value])
	}

	function resetFilter() {
		filterList.value.clear()
		emit('filter', [...filterList.value])
	}
</script>

<template>
	<div class="filter-bar">
		<span class="pad-right filter-icon default-cursor"><Tags size="20" /></span>
		<div class="inner-filter">
			<span class="tag-element pointer"
				:class="{ active : filterList.has(tag) }"
				@click="handleClick(tag)"
				v-for="tag in tags">{{ tag }}</span>
		</div>
		<span class="pad-left filter-icon pointer" @click="resetFilter"><Eraser size="20"/></span>
	</div>
</template>

<style scoped>
	.filter-bar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		width: 100%;
	}

	.pad-right {
		padding-right: 2rem;
	}

	.pad-left {
		padding-left: 2rem;
	}


	.filter-icon {
		font-size: x-large;
	}

	.inner-filter {
		display: flex;
		flex-grow: 1;
		flex-wrap: wrap;
		justify-content: space-evenly;
		align-items: center;
	}

	.tag-element {
		font-size: var(--text-xsm);
	}

	.pointer {
		cursor: pointer;
	}

	.default-cursor {
		cursor: default;
	}

	.active {
		font-weight: bold;
		color: var(--ternary-accent-color);
		text-decoration: underline;
	}
</style>
