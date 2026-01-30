package middleware

import (
	"github.com/gin-gonic/gin"
)

func init() {
	// viper config, headers and stuff
}

func CorsMiddleWare() gin.HandlerFunc {
	return func(c *gin.Context) {
		c.Writer.Header().Set("Access-Control-Allow-Origin", "http://localhost:5173")
	}
}
