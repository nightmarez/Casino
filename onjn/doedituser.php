<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(3 /* Manage Users */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_POST['id'])) {
		header('Location: users.php?self=true');
		die();
	}

	$id = intval($_POST['id']);

	if ($id != getUserId() && !isUserChildOf($id, getUserId())) {
		header('Location: users.php?self=true');
		die();
	}

	if (!isset($_POST['login'])) {
		header('Location: users.php?self=true');
		die();
	}

	$login = htmlspecialchars($_POST['login']);

	if (!isset($_POST['fullname'])) {
		header('Location: users.php?self=true');
		die();
	}

	$fullname = htmlspecialchars($_POST['fullname']);

	$db = new PdoDb();
	$req = $db->prepare('UPDATE `users` SET `login`=:login, `fullname`=:fullname WHERE `id`=:id;');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':fullname', $fullname, PDO::PARAM_STR);
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->execute();

	header('Location: users.php?self=true');
?>