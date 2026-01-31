FROM golang:1.25-alpine
LABEL name=glog
LABEL version=1

RUN apk add bash curl
RUN go install github.com/air-verse/air@latest
RUN mkdir /.cache/
RUN go install github.com/go-delve/delve/cmd/dlv@latest
RUN chown -R 1000:1000 /.cache/ /go
WORKDIR /src/

USER 1000:1000

EXPOSE 8080/tcp
CMD ["air", "-c", ".air.toml"]
