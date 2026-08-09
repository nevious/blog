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
