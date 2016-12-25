<?php
	require_once('utils.php');

	if (!isset($_POST['login']) || !isset($_POST['pass']))
	{
		header('Location: /login.php?error=Не указан логин или пароль');
		die();
	}

	$login = $_POST['login'];
	$pass = $_POST['pass'];
	$currlogin = htmlspecialchars($login);
	$currpass = sha1($pass . DB_SALT);

	$db = new PdoDb();
	$req = $db->prepare('SELECT * FROM `users`;');
	$req->execute();

	while (list($id, $login, $pass, $level, $activated, $sms) = $req->fetch(PDO::FETCH_NUM))
	{
		if ($currlogin != $login || $currpass != $pass) {
			continue;
		}

		updateUserActivity($id);
		
		if ($activated)
		{
			setUserCookies($login, $pass);

			if (isset($_POST['redirect']))
			{
				$redirect = htmlspecialchars($_POST['redirect']);
				header('Location: /' . $redirect . '/');
				die();
				break;
			}

			if ($level == 1)
			{
				//header('Location: /admin/');
				header('Location: /lobby/');
			}
			else if ($level == 3)
			{
				//header('Location: /dealer/');
				header('Location: /lobby/');
			}
			else
			{
				//header('Location: /');
				header('Location: /lobby/');
			}
			
			die();
			break;
		}
		else
		{
			header('Location: /activation.php');
			die();
			break;
		}
	}

	header('Location: /login.php?error=Не удалось авторизироваться');
	die();
?>