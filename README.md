## Prerequisites
PHP5.6 and MYSQL
## Setup instructions
Create a mysql database for the forums & create a user with full privileges to that database:  
``mysql``  
``> CREATE DATABASE {db_name}``  
``> CREATE USER '{db_user}'@'localhost' IDENTIFIED BY '{db_password}';``  
``> GRANT ALL PRIVILEGES ON {db_name}.* TO '{db_user}'@'localhost';``  

Go to the base directory of this repo:  
``cd /var/www/html/online`` 
Import the database schema:  
``mysql -u root -p {db_name} < setup/schema.sql``  

## Running with docker

1. Create a **.env** based on the `.env.example` provided
2. Run `docker-compose up -d`

## Repair database settings:
First, in the ``forum`` directory of this repo, copy Settings.example.php to Settings.php  
``cp forum/Settings.example.php forum/Settings.php``

Next, Download the repair_settings tool from SMF: https://wiki.simplemachines.org/smf/Repair_settings.php

Place repair_settings.php in the ``forum`` directory and visit the url, eg: ``127.0.0.1/online/forum/repair_settings.php``  
Fill in the MySQL Database Info section and save.
Reload the page and click **[Restore all settings]** at the bottom, save.

### Important:
Default admin account is ``Wetfish Online`` and the password is ``changeme``. **change it.**



## How do I deploy this container stack?

See [https://github.com/wetfish/production-manifests](https://github.com/wetfish/production-manifests)
for production deployment and full stack dev env info.

For development, to run just this stack, do 
```bash
cp mariadb.env.example mariadb.env
# -> edit, change passwords and other info as needed
cp php.env.example php.env
# -> edit, change passwords to match

docker compose \
  -f docker-compose.dev.yml \
  up -d \
  --build \
  --force-recreate

docker compose -f docker-compose.dev.yml logs -f
```

The service will be available at [http://127.0.0.1:2404](http://127.0.0.1:2404)

## When do I need to rebuild the container?

Only if you touch Dockerfile. \
Application code is not in the php-fpm container image.
