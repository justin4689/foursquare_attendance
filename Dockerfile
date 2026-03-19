FROM richarvey/nginx-php-fpm:2.2.0

COPY . .

ENV WEBROOT /var/www/html/public
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

CMD ["/start.sh"]