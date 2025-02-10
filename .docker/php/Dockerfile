FROM php:8.3-fpm-alpine

ARG USER=git
ARG GROUP=develop
ARG XDEBUG_VERSION=3.3.2

#region install & update dependencies
RUN apk add --no-cache \
    git \
    bash \
    libzip \
    libzip-dev \
    autoconf \
    bind-tools \
    gcc libc-dev make \
    supervisor \
    sudo && \
    apk add --no-cache --virtual .phpize-deps && \
    docker-php-ext-install zip pdo pdo_mysql pcntl

RUN apk add --update linux-headers

RUN apk --no-cache add --virtual .build-deps \
        g++ \
        autoconf \
        make && \
    pecl install xdebug-${XDEBUG_VERSION} && \
    docker-php-ext-enable xdebug pdo_mysql pcntl
#endregion

#region change shell
SHELL ["/bin/bash", "-c"]
#endregion

#region listen xdebug connections
EXPOSE 9003
#endregion

#region add docker host related user
RUN addgroup ${GROUP}
RUN adduser ${USER} --disabled-password -G ${GROUP}
RUN addgroup www-data ${GROUP}
#endregion

#region copy config files
COPY php.ini /usr/local/etc/php/php.ini
COPY supervisor.conf /etc/supervisor/conf.d/supervisor.conf
#endregion

#region install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
#endregion

#region set rights
RUN mkdir -p /tmp/supervisor/
#endregion

#region set rights
RUN chown -R ${USER}:${GROUP} /var/www/html
RUN chown -R ${USER}:${GROUP} /tmp/supervisor
#endregion

USER ${USER}
CMD /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisor.conf
