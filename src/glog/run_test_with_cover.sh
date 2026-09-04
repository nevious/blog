#!/usr/bin/env bash

go clean -testcache
go test ./... -cover -coverprofile=cover.out -coverpkg=./...
go tool cover -html=cover.out -o cover.html

rm cover.out
