package jobs

import (
	"log"
	"glog/blog"
)

type JobQueue struct {
	// The communications channel
	channel chan int
	// the datastore
	store *blog.PostStore
}

func (jq *JobQueue) TriggerReload() {
	select {
		case jq.channel <- 1:
			log.Printf("Successfully send  reload request to job plane")
		default:
			log.Printf("Reload is already scheduled")
	}
}

func (jq *JobQueue) worker() {
	for {
		select {
			case <- jq.channel:
				err := jq.store.Source.Sync()
				if err != nil {
					log.Printf("Sync Job failed: %s", err.Error())
					break
				}

				jq.store.ReloadLoad()
		}
	}
}

func NewJobQueue(store *blog.PostStore) *JobQueue {
	queue := &JobQueue{
		channel: make(chan int, 1), store: store,
	}
	
	go queue.worker()
	return queue
}
