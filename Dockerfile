FROM docker.io/php:5.6-fpm-stretch

RUN set -exu \
	&& addgroup --gid 1101 fishy \
	&& adduser \
			--uid 1101 \
			--ingroup fishy \
			--no-create-home \
			--shell /sbin/nologin \
			--disabled-password \
		fishy \
	&& mkdir -p /var/www/fourm \
	&& chown -R fishy:fishy /var/www

RUN set -exu \
  && DEBIAN_FRONTEND=noninteractive apt update \
	&& DEBIAN_FRONTEND=noninteractive apt install -yq \
	  libpng-dev \
		libfreetype6-dev \
		mailutils

RUN set -exu \
  && docker-php-ext-configure gd --with-freetype-dir=/usr/include/freetype2/ \
	&& docker-php-ext-configure calendar \
	&& docker-php-ext-install calendar exif gd gettext mysql mysqli pdo_mysql

USER fishy

EXPOSE 9000

WORKDIR /var/www/fourm

CMD ["php-fpm"]
