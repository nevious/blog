package main

import (
	"flag"

	"glog/config"
	"glog/api"
	"glog/blog"
	"glog/mdw"
	"glog/sources"
	"glog/jobs"

	"github.com/gin-gonic/gin"
	"github.com/spf13/viper"
)

func main() {
	configDir := flag.String("configDir", "./etc/", "The configuration directory to look in")
	flag.Parse()
	config.Load(*configDir)

	ds :=  sources.NewGitDataSource(
		viper.GetString("service.data_dir"),
		viper.GetString("repository.url"),
		viper.GetString("repository.branch"),
	)

	blog_store := blog.NewPostStore(ds)
	queue := jobs.NewJobQueue(blog_store)
	queue.TriggerReload()
	router := gin.Default()
	router.SetTrustedProxies(nil)
	router.Use(mdw.CorsMdw())
	api.RegisterRoutes(router, blog_store, queue)

	router.Run()
}
