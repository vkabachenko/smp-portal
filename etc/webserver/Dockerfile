FROM yiisoftware/yii2-php:7.1-apache

RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
RUN docker-php-ext-configure imap \
   --with-kerberos \
   --with-imap-ssl
RUN docker-php-ext-install imap
