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

	$db = new PdoDb();
	$db->beginTransaction();

	$req = $db->prepare('UPDATE `places` SET `money`=0, `free`=1 WHERE `id`=:id;');
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->execute();

	$req = $db->prepare('SELECT `room` FROM `places` WHERE `id`=:id;');
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->execute();

	while (list($room) = $req->fetch(PDO::FETCH_NUM))
	{
		$db->commit();
		header('Location: room.php?id=' . $room);
		break;
	}
?>