<?php
	require_once('utils.php');

	if (getAuthorization() === false)
	{
		header('Location: /');
		die();
	}

	$login = htmlspecialchars($_COOKIE['login']);
	$sms = htmlspecialchars($_POST['sms']);

	$db = new PdoDb();
	$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login;');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->execute();

	echo $login;

	while (list($id, $login, $pass, $level, $activated, $targetSms) = $req->fetch(PDO::FETCH_NUM))
	{
		if ($targetSms == $sms)
		{
			$db1 = new PdoDb();
			$req1 = $db->prepare('UPDATE `users` SET `activated`=1 WHERE `id`=:id;');
			$req->bindParam(':id', $id, PDO::PARAM_INT);
			$req->execute();
			header('Location: /');
			die();
		}
		else
		{
			header('Location: /activation.php?error=Введено неправильное число');
			die();
		}

		break;
	}

	header('Location: /activation.php?error=Пользователь не найден');
	die();
?>