package mdw

import (
	"crypto/subtle"
	"crypto/sha256"
	"github.com/gin-gonic/gin"
	"github.com/spf13/viper"
	"net/http"
)

func AuthMdw() gin.HandlerFunc {
	// Hashing the input and test makes sure subtle.ConstantTimeCompare actually
	// is constant. It returns instantly when parameters are of different lengths
	error_message := gin.H{"message": "auth error"}
	error_code := http.StatusUnauthorized
	verify := viper.GetString("service.api_key")
	verifyHash := sha256.Sum256([]byte(verify))

	return func(c *gin.Context) {
		tokenHash := sha256.Sum256([]byte(c.GetHeader("X-Auth-Token")))

		if subtle.ConstantTimeCompare(tokenHash[:], verifyHash[:]) == 0 {
			c.AbortWithStatusJSON(error_code, error_message)
			return
		}

		c.Next()
	}
}
