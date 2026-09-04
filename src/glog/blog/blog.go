package blog

import (
	"log"
	"sync"
	"path/filepath"
	"io/fs"
	"strings"
	"slices"

	"github.com/spf13/viper"
)

// Quick and dirty!
func (store *PostStore) isIgnored(filename string) bool {
	conf := viper.GetStringSlice("service.ignore_files")
	return slices.Contains(conf, filename)
}

func (store *PostStore) loadPosts() (map[string]*Post, error) {
	files := []string{}
	root := store.Source.DataPath()

	err := filepath.WalkDir(root, func(p string, d fs.DirEntry, err error) error {
		// if we encounter an error, we skip the directory
		if err != nil {
			log.Printf("Skipping directory '%s' due to error %v", p, err)
			return fs.SkipDir
		}

		if !d.IsDir() && strings.HasSuffix(d.Name(), ".md") && !store.isIgnored(d.Name()) {
			files = append(files, p)
			log.Printf("Adding file: %v", p)
		}

		return nil
	})

	if err != nil { return nil, err }

	buffer_posts := map[string]*Post{}
	for _, f := range files {
		p, err := parseMarkdown(f)
		if err != nil {
			log.Printf("Error on %s", f)
			return nil, err
		}

		buffer_posts[p.Meta.Slug] = p
	}

	return buffer_posts, nil
}

func (store *PostStore) ReloadLoad() {

	buffer, err := store.loadPosts()
	if err != nil {
		log.Printf("Error encountered when loading posts, not replacing existing")
		log.Printf("Error was: %v", err)
	} else {
		store.Mu.Lock()
		defer store.Mu.Unlock()
		store.Posts = buffer
	}
}

func NewPostStore(ds DataSource) *PostStore {
	return &PostStore{
		Source: ds,
		Posts: nil,
		Mu: sync.RWMutex{},
	}
}
