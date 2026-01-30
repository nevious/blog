FROM node:current-alpine3.23
LABEL name=myvueblog
LABEL version=0

RUN apk add bash curl
USER 1000:1000

WORKDIR /src/

EXPOSE 80/tcp 443/tcp 5173/tcp
CMD ["npm", "run", "dev", "--host"]
