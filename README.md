# Symfony-News
Symfony based news site
### Инструкция запуска проекта

Требования:
* PHP 7.1+ (Curl)
* MySQL
* Composer

## Установка на Debian/Linux с Apache/Nginx:
1. Скачиваем файлы в папку домена на сервере (var/www/domain.name/)
2. Выполняем настройку Apache согласно инструкции [Configuring a Web Server](https://symfony.com/doc/current/setup/web_server_configuration.html)
3. В директории домена выполняем команду `chmod -R 777 var`
4. Установка_ Composer_ `apt-get install composer`
5. Настройка БД
   В файле .env заменить параметры mysql://[user]:[password]@127.0.0.1:3306 в строке DATABASE_URL на данные от пользователя Mysql
   Командой bin/console doctrine:database:create создать базу данных symfony
   Командой php bin/console make:migration создаём миграцию таблиц 
   Командой php bin/console doctrine:migrations:migrate выполняем миграцию

6. Для обновления списка новостей используем команду php bin/console app:update-feeds

## Запуск на локальном сервере или с помощью PHP
1. Переносим файлы в папку домена на локальном сервере
2. Настройка БД
   В файле .env заменить параметры mysql://[user]:[password]@127.0.0.1:3306 в строке DATABASE_URL на данные от пользователя Mysql
   Командой bin/console doctrine:database:create создать базу данных symfony
   Командой php bin/console make:migration создаём миграцию таблиц 
   Командой php bin/console doctrine:migrations:migrate выполняем миграцию
3. Запускаем сервер командой `php -S localhost:80 -t public`
4. Для обновления списка новостей используем команду php bin/console app:update-feeds

# Для удобной загрузки файлов на сервер используйте wget
* `wget https://github.com/NEROXI/Symfony-News/releases/download/Src/Symfony-News.zip`
* Если не установлен unzip то `apt-get install unzip`
* unzip Symfony-News.zip  










