<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'app/bootstrap.php';
require 'private/private.php';
$auth = new \App\Auth\Social\Google($client);

header('Location: ' . $auth->authorizeUrl());
