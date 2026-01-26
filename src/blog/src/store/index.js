// State management with veux, while 'by default'
// the setup gives you pinia (src/stores/counter.js)
// check out later - might need help.

import { createStore } from 'vuex'

export default createStore({
		state: {
			posts: [],
			currentPost: null
		},
		mutations: {
			SET_POSTS(state, posts) {
			  state.posts = posts
			},
			SET_CURRENT_POST(state, post) {
			  state.currentPost = post
			}
		},
		actions: {
			// This is where we'd implement our backend logic
			// for now we just use a list of dummy posts
			fetchPosts({ commit }) {
				const posts = [
					{
						slug: 'hello-world',
						date: '2026-01-25',
						title: 'Hello World',
						content: 'This is my first blog post on a [vue based project](https://vuejs.org/)'
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
				commit('SET_POSTS', posts)
			},
			fetchPostBySlug({ commit, state }, slug) {
				const post = state.posts.find(post => post.slug == slug)
				console.log('Found post: ', post)
				commit('SET_CURRENT_POST', post || null)
			}
		}
	}
)
