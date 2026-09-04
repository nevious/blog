package api_test

import (
	"os"
	"net/http"
	"net/http/httptest"
	"testing"
	"encoding/json"

	"glog/api"
	"glog/blog"
	"glog/config"

	"github.com/gin-gonic/gin"
	"github.com/stretchr/testify/assert"
	"github.com/spf13/viper"
)

type MockDataSource struct {}
func (ds *MockDataSource ) Sync() error { return nil}
func (ds *MockDataSource ) DataPath() string { 
	return "/src/data_current"
}

func setup() (*gin.Engine, *blog.PostStore, *httptest.ResponseRecorder,) {
	ds := &MockDataSource{}
	router := gin.Default()
	store := blog.NewPostStore(ds)
	api.RegisterRoutes(router, store)	

	recorder := httptest.NewRecorder()

	return router, store, recorder
}

func TestMain(m *testing.M) {
	gin.SetMode(gin.TestMode)
	config.Load("/src/etc")

	os.Exit(m.Run())
}

func TestAuthFailsWithMissingToken(t *testing.T){
	// setup
	router, _, recorder := setup()
	req, _ := http.NewRequest("POST", "/git/reload", nil)

	// execute
	router.ServeHTTP(recorder, req)
	result := recorder.Result()
	var response gin.H
	json.Unmarshal(recorder.Body.Bytes(), &response)

	// verify
	assert.Equal(t, http.StatusUnauthorized, result.StatusCode)
	assert.NotNil(t, response)
	assert.Equal(t, "auth error", response["message"])
}

func TestAuthFailsWithEmptyToken(t *testing.T) {
	// setup
	router, _, recorder := setup()
	req, _ := http.NewRequest("POST", "/git/reload", nil)
	req.Header.Add("X-Auth-Token", "")

	router.ServeHTTP(recorder, req)
	result := recorder.Result()
	var response gin.H
	json.Unmarshal(recorder.Body.Bytes(), &response)

	assert.Equal(t, http.StatusUnauthorized, result.StatusCode)
	assert.NotNil(t, response)
	assert.Equal(t, "auth error", response["message"])
}

func TestAuthFailsWithWrongToken(t *testing.T) {
	router, _, recorder := setup()
	req, _ := http.NewRequest("POST", "/git/reload", nil)
	req.Header.Add("X-Auth-Token", "abc")

	router.ServeHTTP(recorder, req)
	result := recorder.Result()
	var response gin.H
	json.Unmarshal(recorder.Body.Bytes(), &response)

	assert.Equal(t, http.StatusUnauthorized, result.StatusCode)
	assert.NotNil(t, response)
	assert.Equal(t, "auth error", response["message"])
}

func TestAuthSucceedsWithCorrectToken(t *testing.T) {
	router, _, recorder := setup()
	req, _ := http.NewRequest("POST", "/git/reload", nil)
	req.Header.Add("X-Auth-Token", viper.GetString("service.api_key"))

	router.ServeHTTP(recorder, req)
	result := recorder.Result()
	var response gin.H
	json.Unmarshal(recorder.Body.Bytes(), &response)

	// Accepted, 202 gives us more freedom on handling the request
	// asynchroniously. So for a future design this might be nice.
	// Also allows for content to be returned if helpful at some point
	assert.Equal(t, http.StatusAccepted, result.StatusCode)
}

func TestPingHandler(t *testing.T){
	router, _, recorder := setup()
	req, _ := http.NewRequest("GET", "/ping", nil)

	router.ServeHTTP(recorder, req)
	result := recorder.Result()
	var response gin.H
	json.Unmarshal(recorder.Body.Bytes(), &response)

	assert.NotNil(t, result)
}
