<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	} 
	else if (!isUserHasAccess(10 /* Rooms */)) {
		echo 'false';
		die();
	}
	else
	{
		if (!isset($_GET['id'])) {
			echo 'false';
			die();
		}

		$id = intval($_GET['id']);

		if (!isset($_GET['money'])) {
			echo 'false';
			die();
		}

		$money = intval($_GET['money']);

		$db = new PdoDb();
		$db->beginTransaction();

		$req = $db->prepare('UPDATE `rooms` SET `money`=`money` + :money WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->bindParam(':money', $money, PDO::PARAM_INT);
		$req->execute();

		$req = $db->prepare('INSERT INTO `logs` (userid, action, item, room, money) VALUES (:userid, :action, :item, :room, :money);');
		$userid = getUserId();
		$action = 'add';
		$item = 0;
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->bindParam(':action', $action, PDO::PARAM_STR);
		$req->bindParam(':item', $item, PDO::PARAM_INT);
		$req->bindParam(':room', $id, PDO::PARAM_INT);
		$req->bindParam(':money', $money, PDO::PARAM_INT);
		$req->execute();

		$db->commit();
		echo 'true';
	}
?>