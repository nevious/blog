FROM node:current-alpine3.23 AS build
WORKDIR /app
COPY src/img-snap-ui/package*.json .
RUN npm ci
COPY src/img-snap-ui/ .
RUN npm run build


FROM nginx:1.27-alpine
COPY --from=build /app/dist /usr/share/nginx/html
RUN chown nginx:nginx /usr/share/nginx/html -R
RUN cat <<-'EOF' > /etc/nginx/conf.d/default.conf
server {
    listen 80;
    location / {
        root /usr/share/nginx/html;
        try_files $uri $uri/ /index.html;
    }
}
EOF

EXPOSE 80
