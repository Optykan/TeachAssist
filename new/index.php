<?php 
require_once 'User.php';

$user = new User('username', 'password');
$user->store();

?>