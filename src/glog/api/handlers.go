package api

import (
	"sort"
	"net/http"

	"glog/blog"
	"glog/jobs"

	"github.com/gin-gonic/gin"

)

type HandlerContext struct {
	store *blog.PostStore
	queue *jobs.JobQueue
}

func (h *HandlerContext) Ping(c *gin.Context) {
	c.JSON(http.StatusOK, gin.H{
		"message": "pong",
	})
}

func (h *HandlerContext) Echo(c *gin.Context) {
	c.JSON(http.StatusOK, gin.H{
		"message": c.Param("slug"),
	})
}

func (h *HandlerContext) GetPosts(c *gin.Context) {
	posts := make([]blog.PostMeta, 0, len(h.store.Posts))

	for _, p := range h.store.Posts {
		posts = append(posts, p.Meta)
	}

	sort.Slice(posts, func(i int, j int) bool {
		return posts[i].Date.After(posts[j].Date)
	})

	c.JSON(http.StatusOK, posts)
}

func (h *HandlerContext) GetPostBySlug(c *gin.Context) {
	slug := c.Param("slug")
	if post, ok := h.store.Posts[slug]; ok {
		c.JSON(http.StatusOK, post)
		return
	}
	c.JSON(http.StatusNotFound, gin.H{"error": "not found"})
}

func (h *HandlerContext) HandleReload(c *gin.Context) {
	h.queue.TriggerReload()
	c.Status(http.StatusAccepted)
}

func NewHandlerContext(store *blog.PostStore, queue *jobs.JobQueue) *HandlerContext {
	return &HandlerContext{store, queue}
}
