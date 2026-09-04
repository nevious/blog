package sources

import (
	"fmt"
	"log"
	"errors"
	"github.com/go-git/go-git/v5"
	"github.com/go-git/go-git/v5/plumbing"
)

type GitSource struct {
	// Target directory to place the git repository in
	Target string
	// The URL to fetch the repository from
	Url string
	// The branch name to checkout
	Branch string
	// The actual git repository
	Repo *git.Repository
}

func (gds *GitSource) cloneRepository() (*git.Repository, error) {
	repo, err := git.PlainClone(gds.Target, false, &git.CloneOptions{
		URL: gds.Url,
		ReferenceName: plumbing.NewBranchReferenceName(gds.Branch), 
		SingleBranch: true,
		Progress: nil,
	})

	if err != nil {
		return nil, err
	}

	return repo, nil
}

func (gds *GitSource) openRepository() error {
	repo, err := git.PlainOpen(gds.Target)

	switch {
		case err == nil:
			break
		case errors.Is(err, git.ErrRepositoryNotExists):
			log.Printf("repository does not exists, attempt to clone")
			repo, err = gds.cloneRepository()
			if err != nil { return err }
			log.Printf("successfully cloned repository")
		default:
			return fmt.Errorf("existing repository could not be opened: %w", err)
		}

	gds.Repo = repo
	return nil
}

// DataSource Interface method
func (gds *GitSource) Sync() error {
	if gds.Repo == nil {
		err := gds.openRepository()
		if err != nil { return err }
	}

	wt, err := gds.Repo.Worktree()
	if err != nil {
		log.Printf("Error getting repository worktree: %s", err.Error())
	}

	// Pass struct literal cause that's just how it is
	err = wt.Pull(&git.PullOptions{
		ReferenceName: plumbing.NewBranchReferenceName(gds.Branch),
		SingleBranch: true,
	})

	switch {
		case err == nil:
			return nil
		case errors.Is(err, git.NoErrAlreadyUpToDate):
			return nil
		default:
			log.Print("unknown error, dereferencing repository.")
			gds.Repo = nil
			return fmt.Errorf("unable to update repository: %w", err)
	}
}

// DataSource Interface method
func (gds *GitSource) DataPath() string {
	return gds.Target
}

// Create a new git data source 
func NewGitDataSource(target, url, branch string) *GitSource {
	return &GitSource{
		Target: target, Url: url, Branch: branch, Repo: nil,
	}
}
