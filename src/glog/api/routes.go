package api

import (
	"github.com/gin-gonic/gin"
)

func RegisterRoutes(r *gin.Engine ) {
	r.GET("/ping", Ping)
	r.GET("/echo/:slug", Echo)
	r.GET("/posts", GetPosts)
	r.GET("/posts/:slug", GetPostBySlug)
	r.GET("/reload", ReloadPosts)
}

