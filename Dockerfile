FROM php:8.2-apache
WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
EXPOSE 80

git config user.name "snzza"


git config user.email "Adipratamarico6@gmail.com"