import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import Sitemap from 'vite-plugin-sitemap'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
    Sitemap({
      hostname: 'https://nevi-blog.netlify.app',
      dynamicRoutes: [
        '/posts/scotland-2026',
        '/posts/i-keep-forgetting-shit',
        '/posts/belpberg-august-26',
        '/posts/interesting-things',
        '/posts/initial'
      ]
    })
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
})
