<?php 
require_once 'core/User.php';

echo "<pre>";
$user = new User('username', 'password');
$user->store();

var_dump($user);
echo "</pre>";

?>