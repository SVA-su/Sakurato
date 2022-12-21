# GitEngine | Новый современный движок

### GitEngine - это новый движок для сайтов написанный на PHP версии 8.0 с полным исходным кодом

#### Движок публично используется на проекте <a href="https://svamc.su">SVA.su!</a> и на хостинге <a href="https://apexnodes.xyz">ApexNodes.xyz</a>

## Поддерживаемые опериционные системы

| OS | Version | Support |
|----------------:|:---------:|:----------------:|
| Ubuntu \ Debian | 1.1.0 | ✔ |
| CentOS | 1.1.0 | ❔ |
| Windows | 1.1.0 | ❌ |

# Установка

#### Устанавливаем PHP 8.0 и его зависимости вместе с nginx, redis, mysql и composer

###### Для нормальной установки компонентов движка, зайдите с root командой `sudo -s`.

```bash
apt install -y git && LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php && add-apt-repository -y ppa:redislabs/redis && apt update && apt install -y php8.0-{cli,curl,fpm,mysql} nginx redis-server mysql-{server,client} && apt purge apache2 && curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

###### Отдельные команды, если нужно установить что-то отдельно.

```bash
apt install -y git
# Добавление новых репозиторий с пакетами
LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php
add-apt-repository -y ppa:redislabs/redis 
# Обновление пакетов
apt update
# Установка php8.0, nginx, redis и mysql
apt install -y php8.0-{cli,curl,fpm,mysql} nginx redis-server mysql-{server,client} 
# Удаление apache2 если он установился с комплектом от php.
apt purge apache2 
# Установка composer.
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

#### Установка движка и его библиотек

###### Сердце движка, если хотите можете изменить путь где будет храниться движок.

```bash
mkdir -p /var/www && cd /var/www && git clone https://github.com/git-engine/gitengine && cd /var/www/gitengine && composer install
```

# Настройка движка

###### В этой категории будет: Настройка прав, Настройка MySQL, Настройка NGINX, Настройка конфигураций движка.

### Настройка прав

```bash
chmod -R 755 /var/www/gitengine
```

### Настройки MySQL базы данных

###### Важный этап! Запомните данные которые вы укажите, такие как название базы данных, имя пользователя и его пароль. Советуется записать в блокнот.

#### Через PhpMyAdmin

```sql
 -- Создаем базу данных --
CREATE DATABASE database_name;
    -- Входим в базу данных --
 use database_name;
        -- Создаем таблицы --
     CREATE TABLE config (
     down int NOT NULL AUTO_INCREMENT,
     PRIMARY KEY (down)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
     INSERT INTO config (down) VALUES ('0');
 UPDATE config SET down = '0' WHERE config.down = 1;
 -- Создание пользователя gitengine с разрешением подключатся только с локальной сети с своим паролем --
CREATE USER 'gitengine'@'127.0.0.1' IDENTIFIED BY 'Password';
GRANT ALL PRIVILEGES ON database_name.* TO 'gitengine'@'127.0.0.1' WITH GRANT OPTION;
```

#### Через терминал

```bash
mysql -u root -p
```

```sql
CREATE DATABASE database_name;
use database_name;
CREATE TABLE config (down int NOT NULL AUTO_INCREMENT,PRIMARY KEY (down)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO config (down) VALUES ('0');
UPDATE config SET down = '0' WHERE config.down = 1;
 -- Создание пользователя gitengine с разрешением подключатся только с локальной сети с своим паролем --
CREATE USER 'gitengine'@'127.0.0.1' IDENTIFIED BY 'Password';
GRANT ALL PRIVILEGES ON database_name.* TO 'gitengine'@'127.0.0.1' WITH GRANT OPTION;

exit
```

### Настройки Web сервера NGINX

#### Рекомендуется удалить автоматически созданный файл конфигураций NGINX

```bash
rm /etc/nginx/sites-enabled/default
```

#### Создаем конфигурационный файл

###### Без этого на сайт не зайдем, создаем и заходим в конфигурацию.

```bash
touch /etc/nginx/sites-enabled/gitengine.conf
nano /etc/nginx/sites-enabled/gitengine.conf
```

##### Nginx конфигурации с SSL

###### Используем тогда когда у вас есть домен и есть SSL.

```conf
server {
 listen 80;
 server_name <DOMAIN>;
    if ($host = <DOMAIN>) {
        return 301 https://$host$request_uri;
    }
}

server {
 server_name <DOMAIN>;
 root /var/www/gitengine/pages/index.php;
 index index.php;

    listen 443 ssl; 
    ssl_certificate /etc/letsencrypt/live/<DOMAIN>/fullchain.pem; 
    ssl_certificate_key /etc/letsencrypt/live/<DOMAIN>/privkey.pem;

    # Если вы создавали сертефикат через certbot, можете раскомментировать.
    # include /etc/letsencrypt/options-ssl-nginx.conf;
    # ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; 

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
}
```

##### Nginx конфигурация без SSL

###### Используем тогда когда у вас нету домена или отсутствует SSL сертефиката.

```conf
server {
#    server_name <DOMAIN>;
    root /var/www/gitengine/pages/index.php;
    index index.php;

    listen 80;

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
}
```

#### Перезагружаем NGINX

```bash
nginx -s reload
```

### Настройка конфигурации движка

#### **/config/database.php**

```php
<?php
return [
    'driver' => 'mysql',
    'host' => 'Айпи MYSQL',
    'user' => 'Имя пользователя',
    'password' => 'Пароль пользователя',
    'db' => [
    'client' => 'Имя базы данных'
    ],
    'redis' => [ // Это нужно для сессий, не удаляем
        'host' => '127.0.0.1',
        'port' => '6379',
        'password' => ''
    ],
];
```

#### **/config/domain.php**

```php
<?php
return [
    'domain' => [
        'main' => 'ДоменноеИмя.ру',
        'main2' => '127.0.0.1'
    ],
    'protocol' => 'https://',
    'protocol2' => 'http://'
];
```
#### **/config/home.php**

```php
<?php
return [
    'root' => "/var/www/gitengine/",
];
```

#### **/config/mail.php**

```php
<?php
return [
    'driver' => 'phpmailer', // Драйвер отправки почты
    'encrypt' => 'ssl', // Шифрование при отправке почты. Есть методы: ssl, tls, none
    'char-set' => 'UTF-8', // Кодировка текста в почте.
    'from' => 'mail@mail.ru', // Почта в заголовке
    'host' => 'smtp.mail.ru', // SMTP сервер
    'user' => 'main@mail.ru', // Имя пользователя(почта)
    'password' => 'Password', // Пароль
    'port' => '465', // Порт SMTP сервер
];
```

#### **/config/time.php**

```php
<?php
return [
    'timezone' => 'Europe/Moscow' // https://www.php.net/manual/ru/timezones.php
];
```

# Разрабочики \ Contributors

#### <a href="https://github.com/Mr666dd">Mr666dd</a> - Главный разработчик, отвественный за код. <br> <a href="https://github.com/AgeraFly">AgeraFly</a> - Разработчик, отвественный за код и остальное в репозитории и организации
