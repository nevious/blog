const API_BASE = import.meta.env.VITE_API_BASE || "http://localhost/"
console.log("environment: %o", import.meta.env)


export async function fetchPosts() {
	const response = await fetch(`${API_BASE}/posts`)
	if (!response.ok) {
		throw new Error(`Unable to fetchposts: ${response}`)
	}
	return response.json()
} 

export async function fetchPostBySlug(slug){
	const response = await fetch(`${API_BASE}/posts/${slug}`)
	if (!response.ok) {
		throw new Error(`Unable to fetch post '${slug}': ${response}`)
	}
	return response.json()
}
