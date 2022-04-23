<?php
require 'libs/lib.php';
$site = new site();


$token = R::findOne('restore', 'code = ?', array($_GET['t']));
if (empty($token)) {
	exit('Такого кода восстановления не существует!');
} 


$user = $site->findUser($token['login']);
if (empty($user)) {
	exit('Произошла ошибка, просьба обратиться к администрации как можно скорее!');
}


if ($token['time'] + 600 < time()) {
	exit('Время на восстановление вышло! Дается 10 минут!');
}

$code = md5($_GET['t']);

$obj = R::load('users', $user['id']);
$obj['password'] = md5($code);
R::store($obj);

exit('Вы успешно восстановили пароль! Ваш новый пароль: ' . $code . '<br>Сменить его вы можете в личном кабинете!');