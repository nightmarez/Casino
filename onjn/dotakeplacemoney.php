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

	$req = $db->prepare('SELECT `room`, `money` FROM `places` WHERE `id`=:id;');
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->execute();

	while (list($room, $money) = $req->fetch(PDO::FETCH_NUM))
	{
		$req = $db->prepare('UPDATE `rooms` SET `money`=`money`+:money WHERE `id`=:room;');
		$req->bindParam(':money', $money, PDO::PARAM_INT);
		$req->bindParam(':room', $room, PDO::PARAM_INT);
		$req->execute();

		$req = $db->prepare('UPDATE `places` SET `money`=0, `free`=1 WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		$req = $db->prepare('INSERT INTO `logs` (userid, action, item, room, money) VALUES (:userid, :action, :item, :room, :totalprice);');
		$userid = getUserId();
		$action = 'sell';
		$item = 0;
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->bindParam(':action', $action, PDO::PARAM_STR);
		$req->bindParam(':item', $item, PDO::PARAM_INT);
		$req->bindParam(':room', $room, PDO::PARAM_INT);
		$req->bindParam(':totalprice', $money, PDO::PARAM_INT);
		$req->execute();

		$db->commit();
		header('Location: room.php?id=' . $room);
		break;
	}
?>