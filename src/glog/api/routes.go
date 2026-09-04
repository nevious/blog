package api

import (
	"glog/mdw"
	"glog/blog"
	"glog/jobs"

	"github.com/gin-gonic/gin"
)

func RegisterRoutes(r *gin.Engine, store *blog.PostStore, queue *jobs.JobQueue) {
	public := r.Group("/")
	public.Use(mdw.CorsMdw())
	handler := NewHandlerContext(store, queue)

	{
		public.GET("/ping", mdw.InjectMdw(), handler.Ping)
		public.GET("/echo/:slug", handler.Echo)
		public.GET("/posts", handler.GetPosts)
		public.GET("/posts/:slug", handler.GetPostBySlug)
	}

	private := r.Group("/git")
	private.Use(mdw.AuthMdw())
	{
		private.POST("/reload", handler.HandleReload)
	}

	r.OPTIONS("/*default", mdw.CorsMdw())
}
