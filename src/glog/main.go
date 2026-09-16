package main

import (
	"flag"
	"fmt"

	"glog/config"
	"glog/api"
	"glog/blog"
	"glog/mdw"
	"glog/sources"
	"glog/jobs"

	"github.com/gin-gonic/gin"
)

// Eventually all this bootstrapping can be moved into it's own package
// and main should really just be an entrypoint to the app.
func main() {
	// Setup configuration
	configDir := flag.String("configDir", "./etc/", "The configuration directory to look in")
	flag.Parse()
	config.Load(*configDir)

	// Setup data store based on configuration and do an initial Sync() and ReloadLoad()
	// to load content into memory.
	// During runtime, this is to be handled via queue.TriggerReload()
	ds :=  sources.NewDataSource()
	blog_store := blog.NewPostStore(ds)
	err := blog_store.Source.Sync()
	if err != nil {
		panic(fmt.Errorf("unable to create store: %w", err))
	}
	blog_store.ReloadLoad()

	// Application plumbing
	queue := jobs.NewJobQueue(blog_store)
	router := gin.Default()
	router.SetTrustedProxies(nil)
	router.Use(mdw.CorsMdw())
	api.RegisterRoutes(router, blog_store, queue)

	router.Run()
}
