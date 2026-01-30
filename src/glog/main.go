package main 

import (
	"github.com/gin-gonic/gin"

	"glog/mdw"
	"glog/api"
	"glog/blog"
)

func init() {
	// Init function to load viper config in later
}

func main() {
	blog.LoadPosts()
	router := gin.Default()
	router.SetTrustedProxies(nil)
	router.Use(middleware.CorsMiddleWare())
	api.RegisterRoutes(router)
	router.Run()
}
