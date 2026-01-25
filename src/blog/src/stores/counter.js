// This is a statemangement with pinia
// while src/store/index.js is a state management with vuex
// Both work, both ok, but pinia seems to be the newer, recommended one

import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useCounterStore = defineStore('counter', () => {
  const count = ref(0)
  const doubleCount = computed(() => count.value * 2)
  function increment() {
    count.value++
  }

  return { count, doubleCount, increment }
})
