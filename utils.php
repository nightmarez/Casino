<?php
	require_once('db.php');

	function isDebugMode()
    {
    	$db = new PdoDb();
    	$req = $db->prepare('SELECT `value` FROM `settings` WHERE `param`="debugmode";');
    	$req->execute();

    	while (list($value) = $req->fetch(PDO::FETCH_NUM))
		{
			if ($value == '1')
			{
				return true;
			}
			else
			{
				return false;
			}

			break;
		}

		return false;
    }

	if (isDebugMode()) {
		// for local testing

		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
	}
	
	require_once('config/vk-config.php');
	require_once('config/fb-config.php');
	require_once('config/ok-config.php');

	function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec) . '.' . mt_rand();
	}

	function adminZoneAccess()
	{
		if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']))
		{
			return false;
		}

		$login = htmlspecialchars($_COOKIE['login']);
		$pass = htmlspecialchars($_COOKIE['pass']);

		$db = new PdoDb();
		$req = $db->prepare('SELECT COUNT(*) FROM users WHERE login=:login AND pass=:pass AND level=1;');
		$req->bindParam(':login', $login);
		$req->bindParam(':pass', $pass);
		$req->execute();
		$count = $req->fetchColumn();
		return $count >= 1;
	}

	function dealerZoneAccess()
	{
		if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']))
		{
			return false;
		}

		$login = htmlspecialchars($_COOKIE['login']);
		$pass = htmlspecialchars($_COOKIE['pass']);

		$db = new PdoDb();
		$req = $db->prepare('SELECT COUNT(*) FROM users WHERE login=:login AND pass=:pass AND level=3;');
		$req->bindParam(':login', $login);
		$req->bindParam(':pass', $pass);
		$req->execute();
		$count = $req->fetchColumn();
		return $count >= 1;
	}

	function lobbyZoneAccess()
	{
		if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']))
		{
			return false;
		}

		if (adminZoneAccess() || dealerZoneAccess())
		{
			return true;
		}

		$login = htmlspecialchars($_COOKIE['login']);
		$pass = htmlspecialchars($_COOKIE['pass']);

		$db = new PdoDb();
		$req = $db->prepare('SELECT COUNT(*) FROM users WHERE login=:login AND pass=:pass AND level=2 AND lobbyaccess=1;');
		$req->bindParam(':login', $login);
		$req->bindParam(':pass', $pass);
		$req->execute();
		$count = $req->fetchColumn();
		return $count >= 1;
	}

	function isAdmin($login, $pass) 
	{
		$db = new PdoDb();
		$login = htmlspecialchars($login);
		$pass = sha1($pass . DB_SALT);
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=1;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':pass', $pass, PDO::PARAM_STR);
		$req->execute();
		$count = $req->fetchColumn();
		return $count >= 1;
	}

	function getUserId($login, $pass)
	{
		$db = new PdoDb();
		$login = htmlspecialchars($login);
		$pass = htmlspecialchars($pass);
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':pass', $pass, PDO::PARAM_STR);
		$req->execute();

		while (list($id) = $req->fetch(PDO::FETCH_NUM))
		{
			return $id;
		}

		return false;
	}

	function isUser($login, $pass) 
	{
		$db = new PdoDb();
		$login = htmlspecialchars($login);
		$pass = sha1($pass . DB_SALT);
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=1;');
		$req->execute();
		$count = $req->fetchColumn();
		return $count >= 1;
	}

	function isUserExists($login)
	{
		$db = new PdoDb();
		$login = htmlspecialchars($login);
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->execute();
		$count = $req->fetchColumn();
		return $count >= 1;
	}

	function getAuthorization()
	{
		if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']))
		{
			return false;
		}

		$db = new PdoDb();
		$login = htmlspecialchars($_COOKIE['login']);
		$pass = $_COOKIE['pass'];
		$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=2;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':pass', $pass, PDO::PARAM_STR);
		$req->execute();

		while (list($id, $login, $pass) = $req->fetch(PDO::FETCH_NUM))
		{
			return $id;
		}

		return false;
	}

	function startsWith($haystack, $needle) {
    	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

	function getUserThmbByLogin($login)
	{
		$filename = $login . '.jpg';

		if (file_exists('thmbs/' . $filename))
		{
			return '/thmbs/' . $filename;
		}
		else
		{
			return '/imgs/avatar.jpg';
		}
	}

	function getUserNameByLogin($login)
	{
		$db = new PdoDb();
		$login = htmlspecialchars($login);
		$req = $db->prepare('SELECT `fullname` FROM `users` WHERE `login`=:login;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->execute();

		while (list($fullname) = $req->fetch(PDO::FETCH_NUM))
		{
			if (strlen($fullname) > 0)
			{
				return $fullname;
			}
		}

		if (strlen($login) > 0 && is_numeric($login))
		{
			return '+' . $login;
		}
		else
		{
			return $login;
		} 
	}

	function getUserLoginById($userId)
	{
		$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM `users` WHERE `id`=:id;');
		$req->bindParam(':id', $userId, PDO::PARAM_INT);
		$req->execute();

		while (list($id, $login, $pass) = $req->fetch(PDO::FETCH_NUM))
		{
			return $login;
		}

		return false;
	}

	function getGameNameById($id)
	{
		$db = new PdoDb();
		$req = $db->prepare('SELECT `title` FROM `programs` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($title) = $req->fetch(PDO::FETCH_NUM))
		{
			return $title;
		}

		return false;
	}

	function getGamePathById($id)
	{
		$db = new PdoDb();
		$req = $db->prepare('SELECT `path` FROM `programs` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($path) = $req->fetch(PDO::FETCH_NUM))
		{
			return $path;
		}

		return false;
	}

	function addUser($login, $pass, $level)
	{
		$db = new PdoDb();
		$login = htmlspecialchars($login);
		$pass = sha1($pass . DB_SALT);
		$level = intval($level);

		$req = $db->prepare('INSERT INTO `users`(`login`, `pass`, `level`) VALUES (:login, :pass, :level);');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':pass', $pass, PDO::PARAM_STR);
		$req->bindParam(':level', $level, PDO::PARAM_INT);
		$req->execute();
	}

	function setUserCookies($login, $pass)
    {
        setcookie('login', $login, time() + 3600 * 100);
        setcookie('pass', $pass, time() + 3600 * 100);
    }
    
    function unsetUserCookies()
    {
        setcookie('login', '', time() - 3600);
        setcookie('pass', '', time() - 3600);
    }

    function updateUserActivity($userId)
    {
    	$userId = intval($userId);
    	$db = new PdoDb();
    	$req = $db->prepare('INSERT INTO `activity` (`userid`, `last`) VALUES (:userId, NOW()) ON DUPLICATE KEY UPDATE `last`=NOW();');
    	$req->bindParam(':userId', $userId, PDO::PARAM_INT);
    	$req->execute();
    }

    function getUserActivity($userid)
    {
    	$userid = intval($userid);
    	$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM  `activity` WHERE  `userid` =:userid;');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();

    	while (list($userid, $last) = $req->fetch(PDO::FETCH_NUM))
		{
			return $last;
		}

		return false;
    }

    function getL10n($const)
    {
    	$lang = 1;

    	if (isset($_COOKIE['lang']))
    	{
    		$lang = intval($_COOKIE['lang']);
    	}

    	$db = new PdoDb();
		$req = $db->prepare('SELECT `id` FROM `consts` WHERE `title`=:const;');
		$const = htmlspecialchars($const);
		$req->bindParam(':const', $const, PDO::PARAM_STR);
		$req->execute();

    	$constid = 0;

    	while (list($id) = $req->fetch(PDO::FETCH_NUM))
		{
			$constid = intval($id);
			break;
		}

		if ($constid == 0)
		{	
			return $const;
		}

		$req = $db->prepare('SELECT `text` FROM `l10n` WHERE `lang`=:lang AND `const`=:constid;');
		$req->bindParam(':lang', $lang, PDO::PARAM_INT);
		$req->bindParam(':constid', $constid, PDO::PARAM_INT);
		$req->execute();

    	while (list($text) = $req->fetch(PDO::FETCH_NUM))
		{
			return $text;
		}

		return htmlspecialchars($const);
    }

    function isChatEnabled() 
    {
    	$db = new PdoDb();
    	$req = $db->prepare('SELECT `value` FROM `settings` WHERE `param`="chat";');
    	$req->execute();

    	while (list($value) = $req->fetch(PDO::FETCH_NUM))
		{
			if ($value == '1')
			{
				return true;
			}
			else
			{
				return false;
			}

			break;
		}

		return true;
    }

    function isSpinNumberVisible()
    {
    	$db = new PdoDb();
    	$req = $db->prepare('SELECT `value` FROM `settings` WHERE `param`="spinvisible";');
    	$req->execute();

    	while (list($value) = $req->fetch(PDO::FETCH_NUM))
		{
			if ($value == '1')
			{
				return true;
			}
			else
			{
				return false;
			}

			break;
		}

		return true;
    }
?>