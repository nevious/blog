package api

import (
	"net/http"
	"github.com/gin-gonic/gin"

	"glog/blog"
	"sort"
)

func Ping(c *gin.Context) {
	c.JSON(http.StatusOK, gin.H{
		"message": "pong",
	})
}

func Echo(c *gin.Context) {
	c.JSON(http.StatusOK, gin.H{
		"message": c.Param("slug"),
	})
}

func GetPosts(c *gin.Context) {
	posts := blog.GetAllPosts()
	sort.Slice(posts, func(i int, j int) bool {
		return posts[i].Title < posts[j].Title
	})
	c.JSON(http.StatusOK, posts)
}

func GetPostBySlug(c *gin.Context) {
	slug := c.Param("slug")
	post := blog.GetPost(slug)

	if post == nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "not found"})
		return
	}

	c.JSON(http.StatusOK, post)
}

func ReloadPosts(c *gin.Context) {
	blog.ReloadLoad()
	c.Status(http.StatusAccepted)
}
