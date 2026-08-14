import { defineStore } from 'pinia'
import { config } from '@/config/config'

export const useSiteStore = defineStore('site', {
	state: () => ({
		title: config.site.title,
		pageTitle: config.site.title,
		description: config.site.description,
		logo: config.site.logo,
	}),

	getters: {
		pageTitel: (state) => state.pageTitle,
		siteTitle: (state) => `${state.title}`
	},

	actions: {
		updatePageTitle(newTitle) {
			this.pageTitle = newTitle
		}
	}
})
