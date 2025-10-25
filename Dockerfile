FROM php:8.3-apache

# Install mysqli and PDO MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Optional: enable mod_rewrite if needed later
RUN a2enmod rewrite
