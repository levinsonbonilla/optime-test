# Optime-test
Optime test en symfony 6.1

# Descripción
El presente proyecto es una prueba para selección a la empresa Optime

# Requerimientos
- PHP = 8.1.1
- MyAQL >= 8.0
- Symfony >= 6.1
- Composer >= 2.1-dev
- node >= 16.14.2
- yarn >= 1.22.17
- git >= 2.35.1

# Instalación

Para una correcta instalación siga los siguientes pasos.

- Clone el proyecto usando la instrucción "git clone urlProyecto" en la ubicación destinada para el mismo.
- Ubíquese en la carpeta "optime-test" usando la consola cliente que prefiera y ejecute "composer install".
- Ubíquese en la carpeta "optime-test" usando la consola cliente que prefiera y ejecute "yarn install".
- Configure la conexión a su base de datos modificando el archivo ".env" ubicado en la carpeta "optime-test", de la siguiente forma.

> * DATABASE_HOST: Host de su base de datos.
> * DATABASE_USER: Nombre de usuario de su base de datos.
> * DATABASE_PASSWORD: Contraseña de su base de datos.
> * DATABASE_PORT: Puerto asignado a su base de datos.
> * DATABASE_NAME: Nombre de la base de datos a usar.

- Configure la conexión a su base de datos de pruebas modificando el archivo ".env.test" ubicado en la carpeta "optime-test", de la siguiente forma.

> * DATABASE_HOST: Host de su base de datos.
> * DATABASE_USER: Nombre de usuario de su base de datos.
> * DATABASE_PASSWORD: Contraseña de su base de datos.
> * DATABASE_PORT: Puerto asignado a su base de datos.
> * DATABASE_NAME: Nombre de la base de datos a usar.

- Ubíquese en la carpeta "optime-test" usando la consola cliente que prefiera y ejecute los siguientes comandos:

> * php bin/console doctrine:database:create --no-interaction "Este comando crea la base de datos en el entorno local, omitir si ya se encuentra creada"
> * php bin/console doctrine:database:create --env=test --no-interaction "Este comando crea la base de datos para el entorno de test, omitir si ya se encuentra creada"
> * php bin/console doctrine:migrations:migrate --no-interaction "Con este comando se ejecutan en entorno local las migraciones"
> * php bin/console doctrine:migrations:migrate --env=test --no-interaction "Con este comando se ejecutan en entorno local las migraciones"
> * Inserte datos pruebas: INSERT INTO `category` (`id`, `name`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1,	'Groceries',	'Grocery items',	1,	'2022-08-30 23:01:33',	'2022-08-30 23:01:33'),
(2,	'Cleaning',	'Cleaning items',	1,	'2022-08-31 02:54:34',	'2022-08-31 02:54:34');

# Ejecución

Para ejecutar el proyecto en ambiente local Realice las siguientes acciones:

- ubicarse en la carpeta "optime-test" usando la consola cliente que prefiera y ejecute el comando "yarn run encore dev --watch".
- ubicarse en la carpeta "optime-test/public" y ejecute el comando "php -S localhost:8050" usando la consola cliente que prefiera y en navegador escribir la URL "http://localhost:8050/".