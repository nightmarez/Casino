<?php
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	require_once('db.php');

	function isUserUnregistered($id = null) {
		if (!isset($id) || $id == null) {
			$id = getUserId();
		}

		$id = intval($id);
		return getUserType($id) <= 0;
	}

	function isUserAdmin($id = null) {
		if (!isset($id) || $id == null) {
			$id = getUserId();
		}

		$id = intval($id);
		return getUserParent($id) == 0;
	}

	function getUserId() {
		if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass'])) {
			return 0;
		}

		$login = htmlspecialchars($_COOKIE['login']);
		$pass = htmlspecialchars($_COOKIE['pass']);

		$db = new PdoDb();
		$req = $db->prepare('SELECT `id` FROM `users` WHERE `login`=:login AND `pass`=:pass;');
		$req->bindParam(':login', $login, PDO::PARAM_STR);
		$req->bindParam(':pass', $pass, PDO::PARAM_STR);
		$req->execute();

		while (list($id) = $req->fetch(PDO::FETCH_NUM)) {
			return $id;
		}

		return 0;
	}

	function getUserParent($id) {
		$id =intval($id);
		$db = new PdoDb();
		$req = $db->prepare('SELECT `parent` FROM `users` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($parent) = $req->fetch(PDO::FETCH_NUM)) {
			return $parent;
		}

		return 0;
	}

	function getUserName($id) {
		$id =intval($id);
		$db = new PdoDb();
		$req = $db->prepare('SELECT `fullname` FROM `users` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($fullname) = $req->fetch(PDO::FETCH_NUM)) {
			return $fullname;
		}

		return 0;
	}

	function getClientName($id) {
		$id =intval($id);
		$db = new PdoDb();
		$req = $db->prepare('SELECT `name` FROM `clients` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($name) = $req->fetch(PDO::FETCH_NUM)) {
			return $name;
		}

		return 0;
	}

	function getUserType($id = null) {
		if (!isset($id) || $id == null) {
			$id = getUserId();
		}

		$id = intval($id);
		$db = new PdoDb();
		$req = $db->prepare('SELECT `usertype` FROM `users` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($usertype) = $req->fetch(PDO::FETCH_NUM)) {
			return $usertype;
		}

		return 0;
	}

	function getUserTypeName($id = null) {
		if (!isset($id) || $id == null) {
			$id = getUserId();
		}

		$id = intval($id);
		$db = new PdoDb();
		$req = $db->prepare('SELECT `name` FROM `usertypes` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($usertype) = $req->fetch(PDO::FETCH_NUM)) {
			return $usertype;
		}

		return 'Undefined';
	}

	function getUserTypeParent($id = null) {
		if (!isset($id) || $id == null) {
			$id = getUserId();
		}

		$id = intval($id);
		$db = new PdoDb();
		$req = $db->prepare('SELECT `parent` FROM `usertypes` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($parent) = $req->fetch(PDO::FETCH_NUM)) {
			if ($parent != 0) {
				return $parent;
			}
		}

		return 0;
	}

	function isTypeChildOf($id, $target) {
		$id = intval($id);
		$target = intval($target);

		if ($id == $target) {
			return false;
		}

		$db = new PdoDb();
		$req = $db->prepare('SELECT `parent` FROM `usertypes` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($parent) = $req->fetch(PDO::FETCH_NUM)) {
			if ($parent == $target) {
				return true;
			} else if ($parent == 0) {
				return false;
			} else if (isTypeChildOf($parent, $target)) {
				return true;
			}
		}

		return false;
	}

	function isTypeParentOf($id, $target) {
		$id = intval($id);
		$target = intval($target);

		if ($id == $target) {
			return false;
		}

		$db = new PdoDb();
		$req = $db->prepare('SELECT `id` FROM `usertypes` WHERE `parent`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($id) = $req->fetch(PDO::FETCH_NUM)) {
			if ($id == $target) {
				return true;
			} else if ($id == 0) {
				return false;
			} else if (isTypeParentOf($id, $target)) {
				return true;
			}
		}

		return false;
	}

	function isUsertypeHasAccess($usertypeid, $areaid) {
		$db = new PdoDb();
		$req = $db->prepare('SELECT `access` FROM `accessmatrix` WHERE `usertype`=:usertype AND `area`=:area;');
		$req->bindParam(':usertype', $usertypeid, PDO::PARAM_INT);
		$req->bindParam(':area', $areaid, PDO::PARAM_INT);
		$req->execute();

		while (list($access) = $req->fetch(PDO::FETCH_NUM)) {
			return $access == 1;
		}

		return false;
	}

	function isUserHasAccess($areaid, $id = null) {
		if (!isset($id) || $id == null) {
			$id = getUserId();
		}

		$id = intval($id);
		$usertypeid = getUserType($id);
		return isUsertypeHasAccess($usertypeid, $areaid);
	}

	function isUserParentOf($id, $target) {
		return isUserChildOf($target, $id);
	}

	function isUserChildOf($id, $target) {
		if ($id == $target) {
			return false;
		}

		$parent = getUserParent($id);

		if ($parent == $target) {
			return true;
		}

		if ($parent == 0) {
			return false;
		}

		return isUserChildOf($parent, $target);
	}

	function getL10n($const) {
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

	function getRoomTitle($id) {
		$db = new PdoDb();
		$req = $db->prepare('SELECT `title` FROM `rooms` WHERE `id`=:id');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($title) = $req->fetch(PDO::FETCH_NUM))
		{
			return $title;
		}

		return '';
	}

	function getProductTitle($id) {
		$db = new PdoDb();
		$req = $db->prepare('SELECT `title` FROM `products` WHERE `id`=:id');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		while (list($title) = $req->fetch(PDO::FETCH_NUM))
		{
			return $title;
		}

		return '';
	}
?>