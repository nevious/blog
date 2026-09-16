package sources

import (
	"testing"
	"path/filepath"

	"github.com/stretchr/testify/assert"
	"github.com/go-git/go-git/v5"
	"github.com/go-git/go-git/v5/plumbing/transport/server"
    "github.com/go-git/go-git/v5/plumbing/transport/client"
	"github.com/go-git/go-billy/v5/osfs"
	"github.com/spf13/viper"
)

func set_default_config() {
	viper.SetDefault("repository.type", "git")
	viper.SetDefault("repository.url", "https://github.com/nevious/blog_posts")
	viper.SetDefault("repository.branch", "testing")
	viper.SetDefault("service.data_dir", "/tmp/test")
}

func TestCreateNewEmptyGitDatasource(t *testing.T) {
	set_default_config()
	repo := NewDataSource()
	ds, ok := repo.(*GitSource)

	assert.True(t, ok)
	assert.NotNil(t, ds)
	assert.Equal(t, viper.GetString("service.data_dir"), ds.Target)
	assert.Equal(t, viper.GetString("repository.url"), ds.Url)
	assert.Equal(t, viper.GetString("repository.branch"), ds.Branch)
	assert.NoDirExists(t, filepath.Join(viper.GetString("service.data_dir"), "blog_post"))

	t.Cleanup(func() {
		viper.Reset()
	})
}

func TestOpenExistingRepository(t *testing.T) {
	set_default_config()
	target := t.TempDir()
	viper.Set("service.data_dir", target)
	git.PlainInit(target, false)

	s := NewDataSource()
	ds, _ := s.(*GitSource)
	err := ds.openRepository()

	assert.NoError(t, err)
	assert.DirExists(t, filepath.Join(target, ".git"))

	t.Cleanup(func() {
		viper.Reset()
	})
}

func TestOpenFaultyRepositoryFails(t *testing.T){
	set_default_config()
	target := t.TempDir()
	viper.Set("service.data_dir", target)
	viper.Set("repository.url", "https://dunmatter")
	viper.Set("repository.branch", "dunmatter")
	s := NewDataSource()
	ds, _ := s.(*GitSource)
	err := ds.openRepository()

	assert.Error(t, err)
	assert.Contains(t, err.Error(), "dial tcp: lookup dunmatter")

	t.Cleanup(func() {
		viper.Reset()
	})
}

func TestCloneRepositoryIfItDoesNotExist(t *testing.T){
	set_default_config()
	remoteRepo := t.TempDir()
	remoteUrl := "mock://" + remoteRepo
	viper.Set("repository.url", remoteUrl)

	// Make our own protocol
	loader := server.NewFilesystemLoader(osfs.New("/"))
	client.InstallProtocol("mock", server.NewClient(loader))

	target := t.TempDir()
	viper.Set("repository.data_dir", target)
	_, err := git.PlainInit(remoteRepo, true)
	assert.NoError(t, err)

	s := NewDataSource()
	ds, _ := s.(*GitSource)
	err = ds.openRepository()
	assert.Error(t, err)
	// Clone on an empty repository fails, which is not ideal
	// "good enough" to verify the logic.
	assert.Contains(t, err.Error(), "remote repository is empty")
	assert.NotEqual(t, remoteRepo, target)

	t.Cleanup(func() {
		viper.Reset()
	})
}

func TestSyncOnExistingRepository(t *testing.T){
	set_default_config()
	target := t.TempDir()
	viper.Set("service.data_dir", target)

	ds := NewDataSource()
	err := ds.Sync()
	assert.NoError(t, err)
	// go-git does not create a suibdir
	assert.DirExists(t, filepath.Join(target, ".git"))
	assert.FileExists(t, filepath.Join(target, "README.md"))

	t.Cleanup(func() {
		viper.Reset()
	})
}

func TestGetDatapath(t *testing.T) {
	set_default_config()
	target := t.TempDir()
	viper.Set("service.data_dir", target)

	ds := NewDataSource()
	path := ds.DataPath()

	assert.Equal(t, target, path)

	t.Cleanup(func() {
		viper.Reset()
	})
}
