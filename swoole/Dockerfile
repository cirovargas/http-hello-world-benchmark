FROM php:8.5-cli

# Build dependencies
RUN apt-get update && apt-get install -y \
    git \
    build-essential \
    libssl-dev \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libbrotli-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Swoole before building it
RUN docker-php-ext-install sockets

# Build liburing >= 2.8 from source (apt ships older versions)
RUN git clone --depth 1 --branch liburing-2.8 https://github.com/axboe/liburing.git /tmp/liburing \
    && cd /tmp/liburing \
    && ./configure --prefix=/usr/local \
    && make -j"$(nproc)" \
    && make install \
    && ldconfig \
    && rm -rf /tmp/liburing

# Build Swoole 6.2.0 from source with io_uring enabled
RUN git clone --depth 1 --branch v6.2.0 https://github.com/swoole/swoole-src.git /tmp/swoole-src \
    && cd /tmp/swoole-src \
    && phpize \
    && ./configure \
        --enable-swoole-uring \
        --enable-sockets \
        --enable-mysqlnd \
        --enable-swoole-curl \
        --enable-brotli \
        --enable-uring-socket \
        --enable-iouring \
        --with-liburing-dir=/usr/local \
    && make -j"$(nproc)" \
    && make install \
    && echo "extension=swoole.so" > /usr/local/etc/php/conf.d/swoole.ini \
    && rm -rf /tmp/swoole-src

WORKDIR /app
COPY server.php .

EXPOSE 9501

CMD ["php", "server.php"]
