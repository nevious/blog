# About N E V I

This is a personal digital garden of Chrigu Schläppi from Switzerland. Its main purpose was to build it as a learning project tapping into different software development concepts. 

## About the author
<img style="float: left;margin-right:1rem;" src="https://snap.nevious.ch/store/c-headshot" />

Linux Nanny by trait, interested in programming, technology, people and their stories - fictional or not.

For the future I intend to have this blog reflect the chaos goblin that is me. This includes professional topics I may encounter at work but also personal hobbies like mountaineering and discovering life as I move along.

Rather than aiming for frontpage articles, I invite friends and family to come by and have a look.

## About the blog

As per the introduction, this blog is a learning project before anything else. Its content is hosted in a [github repository](https://github.com/nevious/blog_posts), while the application is kept separated [here](https://github.com/nevious/blog). It's built around 4 major components.

### The Backend

Built with **Go** and **Gin**. Unlike other blogs this one is built around being able to blog via textfiles and git version control. Glog (as I came to call it) uses a Git repository as its source and stores the **Markdown** files in its internal state. This removes the need for database complexity and lets the application be completely ephemeral.

The publish workflow is simply an API-Call to an Endpoint that returns `202 Accepted` immediately. The actual `git pull` is performed asynchronously via an internal plane using Go channels.

It's currently published to the DigitalOcean App Platform. The aim is to host this in my lab as well.

### The Frontend

First time writing any meaningful Javascript. Vue was recommended to me and I got curious. It's built around the idea of SPA and parsing Markdown for the content.

It's hosted on Netlify.

### The "Snap"

Written in **PHP/Symfony** to serve as a single source of truth for images used in this blog. It retrieves images and caches them as **.webp** before handing them out.

This part is hosted on my lab kubernetes cluster.

### The Snap UI 🤖✨

Is also hosted on my lab kubernetes cluster, but not publicly accessible. It's an administrative dashboard implemented in **React** and **TypeScript**. It provides a somewhat clean and simple dashboard to manage the images used in this blog. 
