---
title: "Markdown Formatting Showcase"
date: "2026-01-30"
author: "Your Name"
slug: geept-show-off
---

# Heading 1

## Heading 2

### Heading 3

---

## Text formatting

- *Italic text*
- **Bold text**
- ***Bold + Italic***
- ~~Strikethrough~~
- `Inline code`

> This is a blockquote.
>  
> It can span multiple lines.

---

## Lists

### Unordered list

- Item one
- Item two
  - Nested item
  - Nested item
- Item three

### Ordered list

1. First item
2. Second item
3. Third item

---

## Links & Images

[Vue.js Website](https://vuejs.org/)

![Alt text for image](https://placehold.co/400x200/png)

---

## Code blocks

### JavaScript

```js
function hello(name) {
  console.log(`Hello, ${name}!`)
}
```

### Golang

```go
func loadPost(path string) (*Post, error) {
	f, err := os.Open(path)
	if err != nil { return nil, err}

	defer f.Close()

	var meta PostMeta
	content, err := frontmatter.Parse(f, &meta)
	if err != nil { return nil, err }

	return &Post{Meta: meta, Content: string(content)}, nil
}
```

