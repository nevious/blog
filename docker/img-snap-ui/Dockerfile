FROM node:current-alpine3.23

RUN apk add bash curl
USER 1000:1000
WORKDIR /app

EXPOSE 5174/tcp

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
