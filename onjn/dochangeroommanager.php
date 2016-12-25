<?php
	require_once('utils.php');

	if (!isUserHasAccess(10 /* Rooms */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_GET['id'])) {
		return 'false';
		die();
	}

	$id = intval($_GET['id']);

	if (!isset($_GET['managerid'])) {
		return 'false';
		die();
	}

	$managerid = intval($_GET['managerid']);

	$db = new PdoDb();
	$req = $db->prepare('UPDATE `rooms` SET `owner`=:managerid WHERE `id`=:id;');
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->bindParam(':managerid', $managerid, PDO::PARAM_INT);
	$req->execute();

	echo getUserParent($managerid) . '|' . getUserName(getUserParent($managerid));
?>