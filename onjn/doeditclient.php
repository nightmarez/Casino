<?php
	require_once('utils.php');

	if (!isUserHasAccess(16 /* Clients */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_POST['id'])) {
		header('Location: clients.php');
		die();
	}

	$id = intval($_POST['id']);

	if (!isset($_POST['name'])) {
		header('Location: clients.php');
		die();
	}

	$name = htmlspecialchars($_POST['name']);

	$db = new PdoDb();
	$req = $db->prepare('UPDATE `clients` SET `name`=:name WHERE `id`=:id;');
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->bindParam(':name', $name, PDO::PARAM_STR);
	$req->execute();

	header('Location: client.php?id=' . $id);
?>