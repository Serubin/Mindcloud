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
define ('USER', 'mindcloud');
define ('PASSWORD', '87654321');
define ('DATABASE', 'dev_greymatters');

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

// Specify cookie security details
define("SECURE", false);
define("DOMAIN", "mindcloud.loc");

// authorized user ids
define('RUBIN', 0);
define('SHULLICK', 1);
