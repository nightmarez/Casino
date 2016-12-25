<?php
	require_once('utils.php');

	if (!isset($_POST['login']) || !isset($_POST['pass']) || !isset($_POST['pass2']))
	{
		header('Location: /register.php');
		die();
	}

	$login = $_POST['login'];
	$pass = $_POST['pass'];
	$pass2 = $_POST['pass2'];

	if (!preg_match('/^\{?[0-9]{11}\}?$/', $login)) {
		header('Location: /register.php?error=Введите корректный номер телефона');
		die();
	}

	if ($pass != $pass2)
	{
		header('Location: /register.php?error=Пароли не совпадают');
		die();
	}

	if (strlen($pass) > 15)
	{
		header('Location: /register.php?error=Слишком длинный пароль');
		die();
	}

	if (strlen($pass) < 4)
	{
		header('Location: /register.php?error=Слишком короткий пароль');
		die();
	}

	if (isUserExists($login))
	{
		header('Location: /register.php?error=Такой пользователь уже зарегистрирован');
		die();
	}

	$smsText = rand(10000, 32000);
	require_once('sms.php');
	$login = htmlspecialchars($login);
	sendSms($login, 'registration: ' . $smsText);

	$db = new PdoDb();
	$pass = sha1($pass . DB_SALT);
	$req = $db->prepare('INSERT INTO `users` (`login`, `pass`, `level`, `activated`, `sms`, `money`, `lobbyaccess`) VALUES (:login, :pass, 2, 1, :smsText, 1000, 1);');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':pass', $pass, PDO::PARAM_STR);
	$req->bindParam(':smsText', $smsText, PDO::PARAM_INT);
	$req->execute();

	setUserCookies($login, $pass);
	header('Location: /activation.php');
?>