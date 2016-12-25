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

	$db = new PdoDb();
	$req = $db->prepare('UPDATE `clients` SET `banned`=1 WHERE `id`=:id;');
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->execute();

	header('Location: client.php?id=' . $id);
?>