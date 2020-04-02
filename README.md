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

## Repair database settings:
First, in the ``forum`` directory of this repo, copy Settings.example.php to Settings.php  
``cp forum/Settings.example.php forum/Settings.php``

Next, Download the repair_settings tool from SMF: https://wiki.simplemachines.org/smf/Repair_settings.php

Place repair_settings.php in the ``forum`` directory and visit the url, eg: ``127.0.0.1/online/forum/repair_settings.php``  
Fill in the MySQL Database Info section and save.
Reload the page and click **[Restore all settings]** at the bottom, save.

### Important:
Default admin account is ``Wetfish Online`` and the password is ``changeme``. **change it.**
