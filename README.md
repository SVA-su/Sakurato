# GitEngine
Мой Собственный Движок на PHP 8.0, скоро будет система роутов
# Требования
Для движка нужно: php8.0, cli, curl, Веб-сервер: Nginx/Apache, Composer

Для отображения страниц, помещайте файлы в /templates/site/, с окончанием .php

# Установка

Можно установить данный движок командами:

<code> git clone https://github.com/mr666dd/gitengine </code>

# Настройка
1. Создаем базу данных Mysql и выполняем sql запрос:

<code>CREATE TABLE `config` (`down` int NOT NULL DEFAULT '0');
	INSERT INTO `config` (`down`) VALUES (0);</code>
Илиже импортируйте GitEngine.sql файл

В папке config/ настройте как вам нужно. 
После установки, зайдите в корневой каталог сайта, и напишите

composer install

# Выбор веб-сервера

  Так как я написал его под Nginx, советую использовать его. Вот и конфигурация:
  
 
	
	server {
	server_name <DOMAIN>;
	root <ВАШ КАТАЛОГ>/pages/index.php;
	index index.php;
    location / {
        try_files $uri /index.php?$args;
    }
    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param PHP_VALUE "upload_max_filesize = 100M \n post_max_size=100M";
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTP_PROXY "";
        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
    }
    listen 443 ssl; 
    ssl_certificate /etc/letsencrypt/live/<DOMAIN>/fullchain.pem; 
    ssl_certificate_key /etc/letsencrypt/live/<DOMAIN>/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; 
}
	

А как же Apache? А я не знаю...
