<?php
// Start session for Cart/POS/Language features
if(!isset($_SESSION)) { session_start(); }
ini_set('display_errors', 1);
error_reporting(E_ALL);
define('BASEURL', 'http://localhost/emenu');

// Load Core Files
require_once '../app/Config/Database.php';
require_once '../app/config/lang_helper.php';
require_once '../core/App.php';
require_once '../core/Controller.php';

// Initialize Application
$app = new App();