<?php

// Start the output buffer
ob_start(); 

// default time zone
date_default_timezone_set('America/New_York');

// database credentials
define ('HOST', 'localhost');
define ('USER', 'mindcloud');
define ('PASSWORD', '87654321');
define ('DATABASE', 'mindcloud');

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);