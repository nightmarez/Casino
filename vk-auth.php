<?php
	require_once('config/vk-config.php');
	require_once('utils.php');

	if (isset($_GET['code']))
	{
		$code = $_GET['code'];
		$token = getVkToken($code);

		$login = 'vk:' . $token['user_id'];
		$pass = $token['access_token'];

		$db = new PdoDb();
	    $db->beginTransaction();

		if (!isUserExists($login))
		{
			$req = $db->prepare('INSERT INTO `users` (`login`, `pass`, `level`, `activated`, `sms`, `money`, `lobbyaccess`) VALUES (:login, :pass, 2, 1, "", 10000, 1);');
			$req->bindParam(':login', $login, PDO::PARAM_STR);
			$req->bindParam(':pass', $pass, PDO::PARAM_STR);
			$req->execute();

			setUserCookies($login, $pass);
			header('Location: /');
		}
		else
		{
			$req = $db->prepare('UPDATE `users` SET `pass`=:pass WHERE `login`=:login;');
			$req->bindParam(':login', $login, PDO::PARAM_STR);
			$req->bindParam(':pass', $pass, PDO::PARAM_STR);
			$req->execute();

			setUserCookies($login, $pass);
			header('Location: /');
		}

		//////////////////////////////////////////////////////////////////////////////////////////////////////////

		$params = array(
			'uids'         => explode(':', $login)[1],
			'fields'       => 'photo_big',
			'access_token' => $pass
		);

		$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
		$url = $userInfo['response'][0]['photo_big'];
		$tmp = 'thmbs/tmp:' . $login . '.' . end(explode('.', $url));
		file_put_contents($tmp, fopen($url, 'r'));
		$dst = 'thmbs/' . $login . '.jpg';
		if (file_exists($dst))
		{
			unlink($dst);
		}
		list($width, $height) = getimagesize($tmp);
		$thumb = imagecreatetruecolor(64, 64);
		$source = imagecreatefromjpeg($tmp);
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, 64, 64, $width, $height);
		imagejpeg($thumb, $dst);
		imagedestroy($thumb);
		imagedestroy($source);
		unlink($tmp);

		//////////////////////////////////////////////////////////////////////////////////////////////////////////

		$fullName = htmlspecialchars($userInfo['response'][0]['first_name'] . ' ' . $userInfo['response'][0]['last_name']);

		$req = $db->prepare('UPDATE `users` SET `fullname`=:fullName WHERE `login`=:login;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':fullname', $fullname, PDO::PARAM_STR);
		$req->execute();
			
		$db->commit();
	}
	else
	{
		header('Location: ' . genVkAuthLink());
	}
?>