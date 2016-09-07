<?php 
require_once 'User.php';

$user = new User('username', 'password');
$user->store();

echo "<pre>";
print_r($user);
echo "</pre>";

?>