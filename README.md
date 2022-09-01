# GitEngine
Мой Собственный Движок написанный PHP 8.0, с полным исходным кодом, который распространяется по лицензии Apache 2.0, которая позволяет свободно изменять и распространять изменённые копии, за исключением названия.

На данный момент он используется на <a href="https://discord.gg/jndTtABqex">ApexnNodes</a>, и на <a href="https://svamc.su">SVA.su!</a>.

# Преймущества движка

Поддержка нескольких сайтов на 1 движке,

Быстрая обработка запроса(С CloudFlare - 200-400ms, без него примерно так же),

client - встроеный файл-помощник(php client info для информации),

# Требования
Для движка нужно:

php8.0, 

php8.0-cli, 

php8.0-curl, 

php8.0-fpm,

php8.0-mysql; 

Redis и Mysql(Можно и MariaDB, но, я не советую);

Веб-сервер: Nginx;

Composer

# Для отображения страниц:

В папке templates/site создать папку с вашим доменом,

Туда можете помещать любые файлы, с любым расширением(по умолчанию ставьте .php).

# Установка

Можно установить данный движок командами:

```bash
# Устанавливаем git

apt install git

# Копируем движок

git clone https://github.com/mr666dd/gitengine 

# Добавляем репозитории PHP, Redis

LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php

add-apt-repository ppa:redislabs/redis -y

# Обновляем список пакетов

apt update

# Добавление универсального репозитория, если вы в Ubuntu 18.04

apt-add-repository universe

# Устанавливаем зависимости

apt install php8.0-{cli,curl,fpm,mysql} nginx redis-server, mysql-{server,client}

curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем PHP зависимости

composer install

# Изменяем права на файлы

cd ПАПКА ПРОЕКТА/

chmod 777 * -R
```

# Настройка
1. Создаем базу данных Mysql и выполняем sql запрос:

```sql
CREATE DATABASE database_name;
	use database_name;
    	CREATE TABLE config (
    	down int NOT NULL AUTO_INCREMENT,
    	PRIMARY KEY (down)
    	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    	INSERT INTO config (down) VALUES ('0');
	UPDATE config SET down = '0' WHERE config.down = 1;
```

2. В папке config/ настройте каждый файл как вам нужно. 

# Выбор веб-сервера

  Так как я написал его под Nginx, советую использовать его. Под Apache - вам придётся придумывать что то. Вот и конфигурация:
  
 
```
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
```


