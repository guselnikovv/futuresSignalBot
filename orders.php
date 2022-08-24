<?php
include 'App/Main.php';
include 'App/Check.php';

function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

$check = new Check();
$users = $check->getAllUsers();

foreach ($users as $user){
    $main = new Main($user['id']);
    $main->checkOrders();
}
