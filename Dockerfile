FROM yiisoftware/yii2-php:8.3-apache

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
WORKDIR /app
COPY . .
 

RUN  composer update
RUN chmod +x /app/entrypoint.sh 

ENTRYPOINT ["bash", "/app/entrypoint.sh"]

EXPOSE 80