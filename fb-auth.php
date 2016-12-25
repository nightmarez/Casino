<?php
	require_once('config/fb-config.php');
	require_once('utils.php');

	if (isset($_GET['code']))
	{
		$code = $_GET['code'];
		$token = getFbToken($code);
		$pass = $token['access_token'];

		$params = array('access_token' => $pass);
	    $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

	    if (isset($userInfo['id']))
	    {
	        $login = 'fb:' . $userInfo['id'];

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

			$params = array('access_token' => $pass);
			$userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);
			$url = 'http://graph.facebook.com/' . $userInfo['id'] . '/picture?type=large';
			$tmp = 'thmbs/tmp:' . $login . '.jpg';
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

			$fullName = htmlspecialchars($userInfo['name']);
			$req = $db->prepare('UPDATE `users` SET `fullname`=:fullName WHERE `login`=:login;');
			$req->bindParam(':login', $login, PDO::PARAM_STR);
			$req->bindParam(':fullName', $fullName, PDO::PARAM_STR);
			$req->execute();
			
			$db->commit();
	    }
	    else
	    {
	    	header('Location: /');
	    }
	}
	else
	{
		header('Location: ' . genFbAuthLink());
	}
?>