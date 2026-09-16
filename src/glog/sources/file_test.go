package sources

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"github.com/spf13/viper"
)


func TestCreateNewEmptyFileDataSource(t *testing.T){
	set_default_config()
	viper.Set("repository.type", "file")
	s := NewDataSource()

	_, ok := s.(*FileSource)
	assert.True(t, ok)

	t.Cleanup(func() {
		viper.Reset()
	})
}
