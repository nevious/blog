package main

import (
	"os"
	"fmt"

	"github.com/gin-gonic/gin"
	"github.com/spf13/viper"

	"glog/api"
	"glog/blog"
	"glog/mdw"
)

// Avoid using init(), apparently it's dragons territory.
func initConfig() {
	/*
	 * I think this could be handled using GIN_MODE, which is also handled
	 * by Gin itself, see output:
	 * backend-1  | [GIN-debug] [WARNING] Running in "debug" mode. Switch to "release" mode in production.
	 * backend-1  |  - using env:	export GIN_MODE=release
	 * backend-1  |  - using code:	gin.SetMode(gin.ReleaseMode)
	 */
	configFile, ok := os.LookupEnv("MODE"); if !ok {
		configFile = "config"
	}

	viper.SetConfigName(configFile)
	viper.AddConfigPath("./etc/")
	viper.AddConfigPath(".")
	viper.SetConfigType("toml")

	err := viper.ReadInConfig()
	if err != nil {
		panic(fmt.Sprintf("Error loading config: %v", err))
	}
	
	// Apply enviornment defaults. Gin's default router
	// accepts to port only through environment variables
	viper.SetDefault("service.port", 8080)
	os.Setenv("PORT", viper.GetString("service.port"))
}

func main() {
	initConfig()
	blog.ReloadLoad()
	router := gin.Default()
	router.SetTrustedProxies(nil)
	router.Use(middleware.CorsMiddleWare())
	api.RegisterRoutes(router)
	router.Run()
}
