<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(3 /* Add Users */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_POST['login'])) {
		header('Location: adduser.php');
		die();
	}

	$login = htmlspecialchars($_POST['login']);

	if (!isset($_POST['pass'])) {
		header('Location: adduser.php');
		die();
	}
	
	$pass = sha1(DB_SALT . $_POST['pass']);

	if (!isset($_POST['usertype'])) {
		header('Location: adduser.php');
		die();
	}

	$usertype = intval($_POST['usertype']);

	if (isTypeParentOf($usertype, getUserType())) {
		header('Location: adduser.php');
		die();
	}

	$fullname = '';

	if (isset($_POST['fullname'])) {
		$fullname = htmlspecialchars($_POST['fullname']);
	}

	$db = new PdoDb();
	$db->beginTransaction();

	$req = $db->prepare('INSERT INTO `users` (`login`, `pass`, `usertype`, `fullname`, `parent`) VALUES (:login, :pass, :usertype, :fullname, :parent);');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':pass', $pass, PDO::PARAM_STR);
	$req->bindParam(':usertype', $usertype, PDO::PARAM_INT);
	$req->bindParam(':fullname', $fullname, PDO::PARAM_STR);
	$req->bindParam(':parent', getUserId(), PDO::PARAM_INT);
	$req->execute();

	$id = 0;
	$req = $db->prepare('SELECT `id` FROM `users` ORDER BY `id` DESC LIMIT 0, 1;');
	$req->execute();
	while (list($uid) = $req->fetch(PDO::FETCH_NUM)) {
		$id = $uid;
		break;
	}

	$req = $db->prepare('INSERT INTO `managementlogs` (userid, action, target, date) VALUES (:userid, :action, :target, NOW());');
	$userid = getUserId();
	$action = 'hired';
	$req->bindParam(':userid', $userid, PDO::PARAM_INT);
	$req->bindParam(':action', $action, PDO::PARAM_STR);
	$req->bindParam(':target', $id, PDO::PARAM_INT);
	$req->execute();

	$db->commit();
	header('Location: users.php');
?>