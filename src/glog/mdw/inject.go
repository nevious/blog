package mdw

import (
	"github.com/gin-gonic/gin"
)

func InjectMdw() gin.HandlerFunc{
	return func(c *gin.Context){
		c.Writer.Header().Set(
			"X-Dummy", "Nothing",
		)
	}
}
