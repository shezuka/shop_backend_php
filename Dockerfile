FROM ubuntu:22.04
ARG DEBIAN_FRONTEND=noninteractive

RUN apt update
RUN apt install -y curl unzip software-properties-common pkg-config
RUN add-apt-repository ppa:ondrej/php
RUN apt update
RUN apt install -y php php-cli php-bz2 php-curl php-mbstring php-intl php-xml php-pgsql php-fpm

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --filename=composer --install-dir=/usr/local/bin

WORKDIR /backend_php
COPY . .
RUN composer install

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]
