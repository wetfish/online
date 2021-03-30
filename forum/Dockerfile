FROM fishnet/php5.6-fpm-nginx-alpine

# Check what we need from staging here, build out php extensions
RUN apk add --no-cache \
	libjpeg-turbo \ 
	libjpeg-turbo-dev \
	libpng \
	libpng-dev \
	freetype \
	freetype-dev \
	gettext \
	gettext-dev \
	&& docker-php-ext-configure gd \
		--with-jpeg-dir=/usr/include \
		--with-freetype-dir=/usr/include \
		--with-png-dir=/usr/include \
	&& docker-php-ext-configure calendar \
	&& docker-php-ext-install calendar exif gd gettext mysql mysqli \
	&& apk del --no-cache --purge \
		libjpeg-turbo-dev \
		libpng-dev \
		freetype-dev \
		gettext-dev \
	&& addgroup -S -g 1000 fishy && adduser -u 1000 -S -G fishy fishy

COPY ./docker/forum.conf /etc/nginx/locations.d/forum.conf
COPY --chown=fishy:fishy . /var/www/forum
