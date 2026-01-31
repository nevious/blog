package blog

import (
	"os";
	"strings";
	"sync";
	"path/filepath"
	"io/fs"
	"log"

	"github.com/adrg/frontmatter"
	"github.com/spf13/viper"
)

func init() {
	// load viper config once we introduce it
}

type PostMeta struct {
	Title string `yaml:"title" json:"title"`
	Date string `yaml:"date" json:"date"`
	Slug string `yaml:"slug" json:"slug"`
	Author string `yaml:"author" json:"author"`
}

type Post struct {
	Meta PostMeta `json:"meta"`
	Content string `json:"content"`
}

var (
	posts = map[string]*Post{}
	postMu sync.RWMutex
)

func loadPosts() (map[string]*Post, error) {
	viper.SetDefault("service.data_dir", "data")
	root := viper.GetString("service.data_dir")
	files := []string{}

	err := filepath.WalkDir(root, func(p string, d fs.DirEntry, err error) error {
		// if we encounter an error, we skip the directory
		if err != nil {
			log.Printf("Skipping directory '%s' due to error %v", p, err)
			return fs.SkipDir
		}

		if !d.IsDir() && strings.HasSuffix(d.Name(), ".md") {
			files = append(files, p)
		}

		return nil
	})
	if err != nil { return nil, err }

	buffer_posts := map[string]*Post{}
	for _, f := range files {
		p, err := loadPost(f)
		if err != nil { return nil, err }

		buffer_posts[p.Meta.Slug] = p
	}


	return buffer_posts, nil
}

func loadPost(path string) (*Post, error) {
	f, err := os.Open(path)
	if err != nil { return nil, err}

	defer f.Close()

	var meta PostMeta
	content, err := frontmatter.Parse(f, &meta)
	if err != nil { return nil, err }

	return &Post{Meta: meta, Content: string(content)}, nil
}

func GetAllPosts() []PostMeta {
	postMu.Lock()
	defer postMu.Unlock()

	list := make([]PostMeta, 0, len(posts))
	for _, p := range posts {
		list = append(list, p.Meta)
	}

	return list
}

func GetPost(slug string) *Post {
	postMu.RLock()
	defer postMu.RUnlock()

	if p, ok := posts[slug]; ok {
		return p
	}

	return nil
}

func ReloadLoad() {
	postMu.Lock()
	defer postMu.Unlock()

	_, err := loadPosts()
	if err != nil {
		log.Printf("Error encountered when loading posts, not replacing existing")
	} else {
		posts = _
	}
}
