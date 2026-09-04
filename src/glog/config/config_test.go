package config

import (
	"testing"
	"github.com/stretchr/testify/assert"
	"github.com/spf13/viper"
)

func TestConfigEnvironmentHint(t *testing.T) {
	Load("/src/etc/")
	assert.Equal(t, "testing/development", viper.GetString("env.hint"))
}
