<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('private/private.php');
require 'app/bootstrap.php';

include_once('private/private.php');

$auth = new \App\Auth\Social\Google($client);

if(!isset($_GET['code'])){
  echo 'Negando servicios';
  die();
}

$user = $auth->getUser($_GET['code']);

echo 'Nombre: '. $user->name . '<br>';
echo '<img src="'.$user->photo.'">' . '<br>';
echo 'Emai: ' . $user->email;
