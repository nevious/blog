// pinia store
import { ref } from 'vue'
import { defineStore } from 'pinia'

export const usePostStore = defineStore('postStore', () => {
	const posts = ref([])
	const currentPost = ref(null)

	function fetchPostBySlug(slug) {
		currentPost.value = posts.value.find(
			post => post.slug == slug
		)
	}

	function getPostIndex() {
		return posts.value.findIndex(post => post.slug == currentPost.value.slug)
	}

	function getNextPost() {
		var index = getPostIndex()
		if ( index >= 0 && index < posts.value.length - 1){
			return posts.value.at(index+1)
		}
		return posts.value.at(0)
	}

	function getPreviousPost(){
		var index = getPostIndex()
		if ( index > 0 ) {
			return posts.value.at(index-1)
		}
		return posts.value.at(-1)
	}

	function fetchPosts() {
		posts.value = [
			{
				slug: 'hello-world',
				date: '2026-01-25',
				title: 'Hello World',
				content: 'This is my first blog post on a [vue based project](https://vuejs.org/)'
			},
			{
				slug: 'test1',
				date: new Date(2026, 1, 1),
				title: 'Test 1',
				content: 'This is test 1'
			},
			{
				slug: 'test2',
				date: new Date(2026, 2, 1),
				title: 'Test 2',
				content: '## Test 2'
			},
			{
				slug: 'tux',
				date: new Date(2026, 3, 1),
				title: 'Say hi to tux!',
				content: '<img src="https://www.markdownguide.org/assets/images/generated/assets/images/tux-1080.webp" alt="Hi o/" style="display:block;width:100px;margin-left:auto;margin-right:auto;padding:1rem;"/>'
			},
			{
				slug: 'long-world',
				date: '2026-01-25',
				title: 'Long World',
				content: `# 'Tis a long world!

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc vel nisi sit amet nibh dapibus commodo non et augue. Duis at vulputate lectus. Vestibulum sodales iaculis nunc, quis vehicula mauris accumsan sit amet. Maecenas nec facilisis felis. Phasellus feugiat neque risus, vel aliquam ante congue volutpat. Vivamus porta fermentum sem, bibendum mattis mi porttitor vel. Vivamus porttitor sit amet tortor ac efficitur.

Curabitur sit amet egestas odio. Mauris velit augue, ullamcorper non urna eu, imperdiet elementum ante. Donec et nisi fermentum, feugiat ligula eu, blandit risus. Pellentesque ex arcu, placerat in lacus eu, tempus bibendum enim. Proin sollicitudin ut odio ac consequat. Cras laoreet eu tortor a posuere. Curabitur a quam vel nulla pulvinar accumsan ut nec leo. Quisque ultrices nunc ligula, in gravida ante feugiat ut. Sed varius tincidunt ligula, sed imperdiet purus ullamcorper eget. Nulla eu orci tellus. Suspendisse ac tincidunt est. Morbi non euismod diam. Donec maximus semper augue. Quisque vestibulum orci ac nibh lacinia laoreet. Mauris id faucibus lorem.

\`\`\`python
class Test:
	def __init__():
		pass

if __name__ == '__main__':
	return main()
\`\`\`
`
					}
				]
			}
		return {
			posts,
			currentPost,
			fetchPosts,
			fetchPostBySlug,
			getPreviousPost,
			getNextPost
		}
	}
)
