<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(15 /* Products */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_POST['id'])) {
		header('Location: storage.php');
		die();
	}

	$id = intval($_POST['id']);

	$db = new PdoDb();
	$req = $db->prepare('UPDATE `products` SET `count`=0 WHERE `id`=:id');
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->execute();

	header('Location: storage.php');
?>