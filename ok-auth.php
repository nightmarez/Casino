<?php
	require_once('config/ok-config.php');
	require_once('utils.php');

	if (isset($_GET['code']))
	{
		$code = $_GET['code'];

		$token = getOkToken($code);
		$pass = $token['access_token'];

		$sign = md5('application_key=' . ok_client_public . 'format=jsonmethod=users.getCurrentUser' . md5($pass . ok_client_secret));

	    $params = array(
	        'method'          => 'users.getCurrentUser',
	        'access_token'    => $pass,
	        'application_key' => ok_client_public,
	        'format'          => 'json',
	        'sig'             => $sign
	    );

	    $userInfo = json_decode(file_get_contents('http://api.odnoklassniki.ru/fb.do' . '?' . urldecode(http_build_query($params))), true);

	    if (isset($userInfo['uid']))
	    {
	        $login = 'ok:' . $userInfo['uid'];
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

			$sign = md5('application_key=' . ok_client_public . 'format=jsonmethod=users.getCurrentUser' . md5($pass . ok_client_secret));

		    $params = array(
		        'method'          => 'users.getCurrentUser',
		        'access_token'    => $pass,
		        'application_key' => ok_client_public,
		        'format'          => 'json',
		        'sig'             => $sign
		    );

		    $userInfo = json_decode(file_get_contents('http://api.odnoklassniki.ru/fb.do' . '?' . urldecode(http_build_query($params))), true);
			$url = $userInfo['pic_1'];
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

			$fullName = htmlspecialchars($userInfo['first_name'] . ' ' . $userInfo['last_name']);
			$req = $db->prepare('UPDATE `users` SET `fullname`=:fullName WHERE `login`=:login;');
			$req->bindParam(':fullName', $fullName, PDO::PARAM_STR);
			$req->bindParam(':login', $login, PDO::PARAM_STR);
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
		header('Location: ' . genOkAuthLink());
	}
?>