<?php
include 'App/Main.php';
include 'App/Check.php';

function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

$check = new Check();
$check_new_signal = $check->checkNewSignal();

if(is_array($check_new_signal)) {
    if(is_array($users = $check->getAllUsers())) {
        foreach ($users as $user) {
          $check_user_signal = $check->checkUserSignal($user['id'], $check_new_signal['id']);
          if($check_user_signal === false){
            echo "<pre>";
            var_dump($check_user_signal);
            echo "</pre>";
            $main = new Main($user['id']);
            $main->startSignal($check_new_signal['id']);
          } else echo 'Пользователь '.$user['id'].' уже в работе.';
        }
    }
}
