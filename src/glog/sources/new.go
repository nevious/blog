package sources

import (
	"log"
	"fmt"
	"glog/blog"
	"github.com/spf13/viper"
	"strings"
)

// Create a new DataSource from config
func NewDataSource() blog.DataSource {
	sourceType := viper.GetString("repository.type")
	target := viper.GetString("service.data_dir")

	switch strings.ToLower(sourceType) {
		case "git":
			log.Printf("creating a new GitSource")
			repoUrl := viper.GetString("repository.url")
			branch := viper.GetString("repository.branch")

			return &GitSource{
				Target: target, Url: repoUrl, Branch: branch, Repo: nil,
			}
		case "file":
			log.Printf("creating a new FileSource")
			return &FileSource{Target: target}
		default:
			panic(fmt.Errorf("invalid configuration for data source type: %s", sourceType))
	}
}
