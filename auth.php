<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'app/bootstrap.php';

$auth = new \App\Auth\Social\GitHub($client);

header('Location: ' . $auth->authorizeUrl());
