<?php

namespace EcommerceTest;

//Edit this data depending on your environment
interface Config{
    //Web site
    const SCHEME = 'http';
    const HOSTNAME = 'localhost';
    const PORT = 80;
    const MAIN_PATH = '';
    const HOME_URL = Config::SCHEME.'://'.Config::HOSTNAME.':'.Config::PORT.Config::MAIN_PATH;

    //Mysql
    const MYSQL_HOSTNAME = 'localhost';
    const MYSQL_USERNAME = 'root';
    const MYSQL_PASSWORD = '';
    const MYSQL_DATABASE = 'stefano';
}
?>