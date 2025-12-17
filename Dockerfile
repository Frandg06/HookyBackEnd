# Usamos la imagen optimizada para Laravel (PHP 8.3 + Nginx)
# Esta imagen YA TIENE Composer y PHP instalados.
FROM serversideup/php:8.3-fpm-nginx

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Copiamos los archivos de tu proyecto Laravel al contenedor
COPY --chown=webuser:webuser . /var/www/html

# Instalamos las dependencias de PHP
# Esto solucionará el error "composer command not found"
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Creamos el enlace simbólico para las imágenes (storage)
RUN php artisan storage:link