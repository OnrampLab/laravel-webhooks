ARG PHP_EXTENSIONS="pdo_sqlite"
FROM thecodingmachine/php:8.0-v4-slim-apache

USER root

WORKDIR /var/www/html

# install pickle
RUN apt-get update && apt-get install -y bash wget
RUN wget -O phive.phar https://phar.io/releases/phive.phar
RUN wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc
RUN gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79
RUN gpg --verify phive.phar.asc phive.phar
RUN chmod +x phive.phar
RUN sudo mv phive.phar /usr/local/bin/phive

USER docker

RUN phive install --force-accept-unsigned --trust-gpg-keys 6DA3ACC4991FFAE5 phpDocumentor
