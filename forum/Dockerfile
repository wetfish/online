FROM php:5.6-fpm-stretch

# Check what we need from staging here, build out php extensions
RUN apt-get update && apt-get install -y \
	apt-utils \  
	libpng-dev \
	libfreetype6-dev \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/freetype2/ \
	&& docker-php-ext-configure calendar \
	&& docker-php-ext-install calendar exif gd gettext mysql mysqli pdo_mysql

EXPOSE 9000
CMD ["php-fpm"]
