# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Instalación

1) Instalar la base de datos.

2) Luego copiar el archivo .env en la carpeta raíz en donde se encuentra la aplicación y reemplazar los datos de conexión a la base de datos:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mydb
DB_USERNAME=XXXX
DB_PASSWORD=XXXXX

DB_HOST2=127.0.0.1
DB_PORT2=3306
DB_DATABASE2=rukas_dbase
DB_USERNAME2=XXXX
DB_PASSWORD2=XXXXX

3) Instalar composer https://getcomposer.org/, entrar en la carpeta raíz de la aplicación (donde se encuentra .env) y ejecutrar 'composer install'

4) Los archivos a ser procesados deben ser almacenados en la carpeta CSV y luego de ser procesados se moverán a importedCSV.

5) Para ejecutar la aplicación deben acceder a http://XXXXXX/CSVtoDB/index.php/import

6) Para que el sistema envíe emails se debe configurar el parámetro:

MAIL_DRIVER=sendmail
MAIL_HOST=localhost
MAIL_PORT=25
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
