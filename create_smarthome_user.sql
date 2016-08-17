CREATE DATABASE smarthomedb;
USE smarthomedb
CREATE USER 'smarthome'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON * . * TO 'smarthome'@'localhost';
FLUSH PRIVILEGES;
quit
