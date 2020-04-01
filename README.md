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
Import wfo schemas:  
``mysql -u root -p {db_name} < setup/schema.sql``  
``mysql -u root -p {db_name} < setup/smf_settings.sql``  

## Repair database settings:
First, download the repair_settings tool from SMF: https://wiki.simplemachines.org/smf/Repair_settings.php  

Place repair_settings.php in the the ``forum`` directory of this repo and visit the url, eg: ``127.0.0.1/online/forum/repair_settings.php``  
Fill in the MySQL Database Info section and save.
Reload the page and click [Restore all settings] at the bottom, save.
