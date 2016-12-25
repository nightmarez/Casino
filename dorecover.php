<?php
	require_once('utils.php');

	if (!isset($_POST['login']))
	{
		header('Location: /recover.php?error=Не указан номер телефона');
		die();
	}

	$login = $_POST['login'];

	if (!preg_match('/^\{?[0-9]{11}\}?$/', $login)) {
		header('Location: /recover.php?error=Введите корректный номер телефона');
		die();
	}

	if (!isUserExists($login))
	{
		header('Location: /recover.php?error=Указанного пользователя не существует');
		die();
	}

	$smsText = rand(10000, 32000);
	require_once('sms.php');
	$login = htmlspecialchars($login);
	sendSms($login, 'registration: ' . $smsText);

	$db = new PdoDb();
	$req = $db->prepare('UPDATE `users` SET `sms`=:smsText, `activated`=0 WHERE `login`=:login;');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':smsText', $smsText, PDO::PARAM_STR);
	$req->execute();

	setUserCookies($login, '');
	header('Location: /recoversetpass.php');
?>