package blog

import (
	"os"
	"github.com/adrg/frontmatter"
)

func parseMarkdown(path string) (*Post, error) {
	f, err := os.Open(path)
	if err != nil { return nil, err}

	defer f.Close()

	var meta PostMeta
	content, err := frontmatter.Parse(f, &meta)
	if err != nil { return nil, err }
	
	meta.normalize()
	return &Post{Meta: meta, Content: string(content)}, nil
}

