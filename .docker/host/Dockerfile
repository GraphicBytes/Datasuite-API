FROM php:8.3.2-apache

# Install system dependencies required for PHP extensions
RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libbz2-dev \
  libgmp-dev \
  libldap2-dev \
  unixodbc-dev \
  libpq-dev \
  libxslt1-dev \
  libtidy-dev \
  libzip-dev \
  libicu-dev \
  libonig-dev \
  libpspell-dev \
  libc-client-dev \
  libkrb5-dev \
  libenchant-2-dev
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN a2enmod rewrite
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-install mysqli bcmath bz2 calendar dba enchant exif gettext gmp intl ldap opcache pdo_mysql pdo_pgsql pgsql pspell soap sockets sysvmsg sysvsem sysvshm tidy xsl zip gd 

RUN echo 'SetEnv ENV ${ENV}' > /etc/apache2/conf-enabled/environment.conf

RUN echo 'SetEnv MYSQL_HOST ${MYSQL_HOST}' > /etc/apache2/conf-enabled/environment.conf
RUN echo 'SetEnv MYSQL_DATABASE ${MYSQL_DATABASE}' > /etc/apache2/conf-enabled/environment.conf
RUN echo 'SetEnv MYSQL_USER ${MYSQL_USER}' > /etc/apache2/conf-enabled/environment.conf
RUN echo 'SetEnv MYSQL_PASSWORD ${MYSQL_PASSWORD}' > /etc/apache2/conf-enabled/environment.conf

RUN echo 'SetEnv WEBSITESALT ${WEBSITESALT}' > /etc/apache2/conf-enabled/environment.conf
RUN echo 'SetEnv TOKENSALT ${TOKENSALT}' > /etc/apache2/conf-enabled/environment.conf
RUN echo 'SetEnv ENCRYPTIONKEY ${ENCRYPTIONKEY}' > /etc/apache2/conf-enabled/environment.conf
RUN echo 'SetEnv SALT ${SALT}' > /etc/apache2/conf-enabled/environment.conf