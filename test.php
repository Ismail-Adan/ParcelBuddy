<?php

require_once('Models/Database.php');

$view = new stdClass();
$view->pageTitle = 'Test';

$db = Database::getInstance();
$connection = $db->getdbConnection();

if ($connection) {
    $view->message = "Connected to the database successfully.";
} else {
    $view->message = "Failed to connect to the database.";
}

require_once('Views/test.phtml');