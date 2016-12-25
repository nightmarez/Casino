<?php
	require_once('utils.php');

	if (!isUserHasAccess(10 /* Rooms */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_POST['id'])) {
		header('Location: rooms.php');
		die();
	}

	$id = intval($_POST['id']);

	if (!isset($_POST['title'])) {
		header('Location: room.php?id=' . $id);
		die();
	}

	$title = htmlspecialchars($_POST['title']);

	$db = new PdoDb();
	$req = $db->prepare('INSERT INTO `places` (`room`, `money`, `free`, `title`) VALUES (:room, 0, 1, :title);');
	$req->bindParam(':room', $id, PDO::PARAM_INT);
	$req->bindParam(':title', $title, PDO::PARAM_STR);
	$req->execute();

	header('Location: room.php?id=' . $id);
?>