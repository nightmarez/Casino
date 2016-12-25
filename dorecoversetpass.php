<?php
	require_once('utils.php');

	if (!isset($_COOKIE['login']))
	{
		header('Location: /recover.php?error=Не указан номер телефона');
		die();
	}

	$login = $_COOKIE['login'];

	if (!preg_match('/^\{?[0-9]{11}\}?$/', $login)) {
		header('Location: /recover.php?error=Введите корректный номер телефона');
		die();
	}

	if (!isUserExists($login))
	{
		header('Location: /recover.php?error=Указанного пользователя не существует');
		die();
	}

	if (!isset($_POST['sms']))
	{
		header('Location: /recoversetpass.php?error=Не указан код из смс');
		die();
	}

	$sms = intval($_POST['sms']);

	$pass = $_POST['pass'];
	$pass2 = $_POST['pass2'];

	if ($pass != $pass2)
	{
		header('Location: /recoversetpass.php?error=Пароли не совпадают');
		die();
	}

	if (strlen($pass) > 15)
	{
		header('Location: /recoversetpass.php?error=Слишком длинный пароль');
		die();
	}

	if (strlen($pass) < 4)
	{
		header('Location: /recoversetpass.php?error=Слишком короткий пароль');
		die();
	}

	$pass = sha1($pass . DB_SALT);

	$db = new PdoDb();
	$db->beginTransaction();

	$req = $db->prepare('SELECT `sms` FROM `users` WHERE `login`=:login;');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->execute();

	while (list($currsms) = $req->fetch(PDO::FETCH_NUM))
	{
		if ($currsms != $sms)
		{
			header('Location: /recoversetpass.php?error=Неправильный код из смс');
			$db->close();
			die();
		}
		
		break;
	}

	$req = $db->prepare('UPDATE `users` SET `pass`=:pass, `activated`=1 WHERE `login`=:login;');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':pass', $pass, PDO::PARAM_STR);
	$req->execute();

	$db->commit();

	setUserCookies($login, $pass);
	header('Location: /');
?>