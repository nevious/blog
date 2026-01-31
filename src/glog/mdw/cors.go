package middleware

import (
	"github.com/gin-gonic/gin"
	"github.com/spf13/viper"
)

func CorsMiddleWare() gin.HandlerFunc {
	viper.SetDefault("headers.allow_origin", "localhost")
	allowedOrigin := viper.GetString("headers.allow_origin")

	// IIGC; this function is called on every request
	// therefore it makes sense to do config stuff before and not directly inside
	return func(c *gin.Context) {
		c.Writer.Header().Set(
			"Access-Control-Allow-Origin", allowedOrigin,
		)
	}
}
