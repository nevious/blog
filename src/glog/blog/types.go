package blog

import (
	"time"
	"strings"
	"sync"
)

// PostMeta reflects the frontmatter of the markdown file
type PostMeta struct {
	Title string `yaml:"title" json:"title"`
	Date time.Time `yaml:"date" json:"date"`
	Slug string `yaml:"slug" json:"slug"`
	Author string `yaml:"author" json:"author"`
	Category []string `yaml:"category" json:"category"`
	Description string `yaml:"description" json:"description"`
	Splash string `yaml:"splash" json:"splash"`
}

// Normalize a Post's tags to lowercase
func (m *PostMeta) normalize() {
	tags := m.Category
	var tags_norm []string

	for _, tag := range tags {
		tags_norm = append(tags_norm, strings.ToLower(tag))
	}

	m.Category = tags_norm
}


// Post consists of the Metadata (frontmatter) and the
// file's content
type Post struct {
	Meta PostMeta `json:"meta"`
	Content string `json:"content"`
}

// The datasource interface defined by the blog-consumers
type DataSource interface {
	Sync() error
	DataPath() string
}

// Container type
type PostStore struct {
	Source DataSource
	Posts map[string]*Post
	Mu sync.RWMutex
}
