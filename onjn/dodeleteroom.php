<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(10 /* Rooms Management */)) {
		header('Location: 403.php');
		die();
	}

	$adminSelfUsers = false;
	if (isUserAdmin() && isset($_POST['self']) && $_POST['self'] == 'true') {
		$adminSelfUsers = true;
	}

	if (!isset($_POST['id'])) {
		if ($adminSelfUsers) {
			header('Location: rooms.php?self=true');
		} else {
			header('Location: rooms.php');
		}
	}

	$id = intval($_POST['id']);

	if (!isUserParentOf(getUserId(), $id)) {
		if ($adminSelfUsers) {
			header('Location: rooms.php?self=true');
		} else {
			header('Location: rooms.php');
		}
	} else {
		$db = new PdoDb();
		$db->beginTransaction();

		$req = $db->prepare('UPDATE `rooms` SET `deleted`=1 WHERE `id`=:id');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		$money = 0;
		$req = $db->prepare('SELECT `money` FROM `rooms` WHERE `id`=:id');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		while (list($roommoney) = $req->fetch(PDO::FETCH_NUM)) {
			$money = $roommoney;
			break;
		}

		$req = $db->prepare('UPDATE `rooms` SET `money`=0 WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		$req = $db->prepare('INSERT INTO `logs` (userid, action, item, room, money) VALUES (:userid, :action, :item, :room, :money);');
		$userid = getUserId();
		$action = 'get';
		$item = 0;
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->bindParam(':action', $action, PDO::PARAM_STR);
		$req->bindParam(':item', $item, PDO::PARAM_INT);
		$req->bindParam(':room', $id, PDO::PARAM_INT);
		$req->bindParam(':money', $money, PDO::PARAM_INT);
		$req->execute();

		$db->commit();

		if ($adminSelfUsers) {
			header('Location: rooms.php?self=true');
		} else {
			header('Location: rooms.php');
		}
	}
?>