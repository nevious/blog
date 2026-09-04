package sources

import (
	"testing"
	"path/filepath"

	"github.com/stretchr/testify/assert"
	"github.com/go-git/go-git/v5"
	"github.com/go-git/go-git/v5/plumbing/transport/server"
    "github.com/go-git/go-git/v5/plumbing/transport/client"
	 "github.com/go-git/go-billy/v5/osfs"
)

func TestCreateNewEmptyGitDatasource(t *testing.T) {
	target := "/tmp/test"
	url := "https://github.com/nevious/blog_posts"
	branch := "main"

	repo := NewGitDataSource(target, url, branch)

	assert.NotNil(t, repo)
	assert.Equal(t, target, repo.Target)
	assert.Equal(t, url, repo.Url)
	assert.Equal(t, branch, repo.Branch)
	assert.NoDirExists(t, filepath.Join(target, "blog_post"))
}

func TestOpenExistingRepository(t *testing.T) {
	target := t.TempDir()
	git.PlainInit(target, false)

	ds := NewGitDataSource(target, "https://dunmatter", "dunmatter")
	err := ds.openRepository()

	assert.NoError(t, err)
	assert.DirExists(t, filepath.Join(target, ".git"))
}

func TestOpenFaultyRepositoryFails(t *testing.T){
	target := t.TempDir()
	ds := NewGitDataSource(target, "https://dunmatter", "dunmatter")
	err := ds.openRepository()

	assert.Error(t, err)
	assert.Contains(t, err.Error(), "dial tcp: lookup dunmatter")
}

func TestCloneRepositoryIfItDoesNotExist(t *testing.T){
	remoteRepo := t.TempDir()
	remoteUrl := "mock://" + remoteRepo

	// Make our own protocol
	loader := server.NewFilesystemLoader(osfs.New("/"))
	client.InstallProtocol("mock", server.NewClient(loader))

	target := t.TempDir()
	_, err := git.PlainInit(remoteRepo, true)
	assert.NoError(t, err)

	ds := NewGitDataSource(target, remoteUrl, "main")
	err = ds.openRepository()
	assert.Error(t, err)
	// Clone on an empty repository fails, which is not ideal
	// "good enough" to verify the logic.
	assert.Contains(t, err.Error(), "remote repository is empty")
	assert.NotEqual(t, remoteRepo, target)
}

func TestSyncOnExistingRepository(t *testing.T){
	target := t.TempDir()
	url := "https://github.com/nevious/blog_posts"
	branch := "testing"

	ds := NewGitDataSource(target, url, branch)
	err := ds.Sync()
	assert.NoError(t, err)
	// go-git does not create a suibdir
	assert.DirExists(t, filepath.Join(target, ".git"))
	assert.FileExists(t, filepath.Join(target, "README.md"))
}

func TestGetDatapath(t *testing.T) {
	target := t.TempDir()
	url := "https://github.com/nevious/blog_posts"
	branch := "testing"

	ds := NewGitDataSource(target, url, branch)
	path := ds.DataPath()

	assert.Equal(t, target, path)
}
