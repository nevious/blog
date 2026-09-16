package sources

type FileSource struct {
	// Target directory to grab from
	Target string
}

// Interface compliance with types.DataSource
// FileSources do not have a sync option
func (fds *FileSource) Sync() error { return nil }

func (fds *FileSource) DataPath() string {
	return fds.Target
}
