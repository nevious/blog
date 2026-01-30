package blog

import (
	"os";
	"strings";
	"sync";
	"path"

	"github.com/adrg/frontmatter";
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

func LoadPosts() error {
	files, err := os.ReadDir("data")
	if err != nil { return err }

	temp := map[string]*Post{}

	for _, f := range files {
		if f.IsDir() || !strings.HasSuffix(f.Name(), ".md") {
			continue
		}

		//p, err := loadPost("data/" + f.Name())
		p, err := loadPost(path.Join("data", f.Name()))
		if err != nil { return err }

		temp[p.Meta.Slug] = p
	}

	postMu.Lock()
	posts = temp
	postMu.Unlock()

	return nil
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
