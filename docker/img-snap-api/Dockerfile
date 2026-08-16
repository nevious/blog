FROM dunglas/frankenphp:1.12.7-php8.5-trixie
LABEL name=img-snap

ARG USER=appuser

RUN apt update
RUN apt upgrade -y

RUN apt -y install bash \
	procps \
	lsof \
	iproute2 \
	git \
	unzip \
    libpng-dev \
    libwebp-dev \
    libjpeg-dev \
    libfreetype-dev

COPY --link \
    --from=ghcr.io/symfony-cli/symfony-cli:latest \
    /usr/local/bin/symfony /usr/local/bin/symfony

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN <<-EOF
	# Use "adduser -D ${USER}" for alpine based distros
	useradd ${USER}
	# Add additional capability to bind to port 80 and 443
	setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp
	# Give write access to /config/caddy and /data/caddy
	chown -R ${USER}:${USER} /config/caddy /data/caddy
EOF

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd

RUN cat << EOF > /usr/local/etc/php/conf.d/custom_memory_limit.ini
memory_limit = 256M;
EOF

ENTRYPOINT ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

USER ${USER}:${USER}
