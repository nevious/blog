package mdw

import (
	"github.com/gin-gonic/gin"
	"github.com/spf13/viper"
)

func CorsMdw() gin.HandlerFunc {
	viper.SetDefault("headers.allow_origin", "http://localhost")
	allowedOrigin := viper.GetString("headers.allow_origin")

	// IIGC; this function is called on every request
	// therefore it makes sense to do config stuff before and not directly inside
	// TODO:
	// I think the common practice is to have a regex of allowed origins
	// If the regex matches, return the Origin, not the regex.
	return func(c *gin.Context) {
		c.Writer.Header().Set(
			"Access-Control-Allow-Origin", allowedOrigin,
		)
		c.Writer.Header().Set(
			"Access-Control-Allow-Methods", "GET",
		)

		c.Next()
	}
}
