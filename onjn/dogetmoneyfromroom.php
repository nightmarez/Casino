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

		$current = 0;
		$req = $db->prepare('SELECT `money` FROM `rooms` WHERE `id`=:id AND `deleted`=0 ORDER BY `id`;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		while (list($c) = $req->fetch(PDO::FETCH_NUM)) {
			$current = $c;
			break;
		}

		if ($current < $money) {
			echo 'false';
			break;
		}

		$req = $db->prepare('UPDATE `rooms` SET `money`=`money` - :money WHERE `id`=:id AND `deleted`=0 ;');
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