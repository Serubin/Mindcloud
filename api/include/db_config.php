<?php

// Start the output buffer
ob_start(); 

// default time zone
date_default_timezone_set('America/New_York');

// database credentials
define ('HOST', 'serubin.net');
define ('USER', 'dev_greymatters');
define ('PASSWORD', 'dWWDNzt8Jcn5p7Ce');
define ('DATABASE', 'dev_greymatters');

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
