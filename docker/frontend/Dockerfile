FROM node:current-alpine3.23
LABEL name=myvueblog
LABEL version=0

RUN apk add bash curl
USER 1000:1000

WORKDIR /src/

EXPOSE 5173/tcp
CMD ["npm", "run", "dev", "--host"]
