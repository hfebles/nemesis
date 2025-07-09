# Stage 1: Build the application
FROM php:8.2-fpm-alpine as builder

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    openssl \
    tokenizer \
    xml \
    gd # Si necesitas manipulación de imágenes

# Set working directory
WORKDIR /app

# Copy composer.json and composer.lock to leverage Docker cache
COPY composer.json composer.lock ./

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy the rest of the application code
COPY . .

# Run Laravel specific commands (e.g., migrations, cache)
# Note: Artisan commands are usually run after the container is deployed and running,
# but if you have commands that can be pre-built, you can add them here.
# For Easypanel, it's often better to run these as "start commands" or "post-deployment scripts".
# RUN php artisan optimize

# Build frontend assets if you have any (e.g., Vite, Webpack)
# If you don't have frontend assets or build them separately, you can remove this.
ARG NODE_VERSION=20
ENV PATH="/opt/node/bin:${PATH}"
RUN npm install -g n && n ${NODE_VERSION} && npm install && npm run build


# Set appropriate permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Expose port (PHP-FPM usually runs on 9000)
EXPOSE 9000

# Stage 2: Final image for production
FROM php:8.2-fpm-alpine

# Install system dependencies needed for runtime
RUN apk add --no-cache \
    nginx \
    libpq \
    libpng \
    libjpeg-turbo \
    freetype

# Copy PHP-FPM configuration if you have custom settings (e.g., in docker/php/production.ini)
# If you have custom PHP settings, uncomment and adjust the path
# COPY docker/php/production.ini /usr/local/etc/php/conf.d/production.ini

# Copy Nginx configuration
# This Nginx configuration is crucial for Laravel. It should point to the public directory.
# You might place this in `docker/nginx/default.conf`
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy application from builder stage
COPY --from=builder /app /app

# Set appropriate permissions again
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Set working directory
WORKDIR /app

# Command to run Nginx and PHP-FPM
# This starts both Nginx and PHP-FPM within the same container.
# For more complex setups, you might consider separate containers for Nginx and PHP-FPM
# using Docker Compose, but Easypanel usually expects a single app container.
CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm"]

