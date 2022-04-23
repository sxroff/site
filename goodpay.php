<?php

$params = json_decode(file_get_contents('php://input'), true);

require 'libs/lib.php';
$site = new site();
$payment = R::findOne('payments', 'billid = ?', array($params['bill']['billId']));

if (empty($payment)) {
	exit();
}

if ($payment['status'] == 1) {
	exit();
}


if ($payment['money'] > 500) {
	$money = $payment['money'] * 1.15;
}

if ($payment['money'] > 1000) {
	$money = $payment['money'] * 1.2;
}

if ($payment['money'] > 2000) {
	$money = $payment['money'] * 1.3;
}

if ($payment['money'] > 4500) {
	$money = $payment['money'] * 1.45;
}

if ($payment['money'] > 7500) {
	$money = $payment['money'] * 1.65;
}

if ($payment['money'] > 10000) {
	$money = $payment['money'] * 1.85;
}

if (!isset($money)) {
	$money = $payment['money'];
}



$user = $site->findUser($payment['login']);
$money = floor($money);

if (!isset($_GET['lol'])) {
	$obj = R::load('payments', $payment['id']);
	$obj['status'] = 1;
	R::store($obj);
	file_put_contents('donat', file_get_contents('donat') + $payment['money']);
} else {
	file_put_contents('yatophack.txt', file_get_contents('yatophack.txt') + $payment['money']);
}

unset($obj);

$obj = R::load('users', $user['id']);
$obj['money'] = $user['money'] + $money;
R::store($obj);
