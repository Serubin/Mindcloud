<?php

// Start the output buffer
ob_start(); 

// default time zone
date_default_timezone_set('America/New_York');

// database credentials
/*define ('HOST', 'serubin.net');
define ('USER', 'dev_greymatters');
define ('PASSWORD', 'dWWDNzt8Jcn5p7Ce');
define ('DATABASE', 'dev_greymatters');*/

// database credentials
define ('HOST', 'localhost');
define ('USER', 'mindcloud_prd');
define ('PASSWORD', 'LXTf6x8HYa2P46WPy9wJ68ZSPHtEabx7AE8txcWXybc5ZWsNkv2PgK2E9NavRwc3');
define ('DATABASE', 'mindcloud_io');

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

// Specify cookie security details
define("SECURE", false);
define("DOMAIN", "mindcloud.io");

// authorized user ids
define('RUBIN', 1);
define('SHULLICK', 2);
