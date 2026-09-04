package config

import (
	"os"
	"fmt"
	"github.com/spf13/viper"
)

func Load(path string) {
	configFile, ok := os.LookupEnv("MODE"); if !ok {
		configFile = "config"
	}

	viper.SetConfigName(configFile)
	viper.AddConfigPath(path)
	viper.SetConfigType("toml")

	err := viper.ReadInConfig()
	if err != nil {
		panic(fmt.Sprintf("Error loading config: %v", err))
	}
	
	// Integrate environment variables into the viper system
	// NOTE: Gin's default router takes the port by enviornment only
	viper.SetDefault("service.port", 8080)
	viper.SetDefault("service.ignore_files", []string{})
	viper.BindEnv("service.api_key", "APP_API_KEY")
	os.Setenv("PORT", viper.GetString("service.port"))
}
