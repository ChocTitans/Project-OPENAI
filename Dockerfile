FROM php:8.0-apache


COPY . /var/www/html/

COPY --chown=daemon:daemon . /var/www/html/

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Install libzip extension
RUN apt-get update && apt-get install -y libzip-dev && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip

RUN mkdir -p /var/www/html/session_data
RUN chmod 733 /var/www/html/session_data
RUN chown www-data:www-data /var/www/html/session_data

# Configure session.save_path and session.cookie_domain
RUN echo "session.save_path = '/var/www/html/session_data'" >> /usr/local/etc/php/php.ini


# Enable the session module
RUN a2enmod rewrite

# Start the Apache web server
CMD ["apache2-foreground"]