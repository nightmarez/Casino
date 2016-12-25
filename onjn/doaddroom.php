<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	} 
	else if (!isUserHasAccess(10 /* Rooms */)) {
		header('Location: 403.php');
		die();
	}
	else
	{
		if (!isset($_POST['title']) || !isset($_POST['owner']) || !isset($_POST['money'])) {
			header('Location: rooms.php');
			die();
		}

		$title = htmlspecialchars($_POST['title']);
		$owner = intval($_POST['owner']);
		$money = intval($_POST['money']);

		if (strlen($title) <= 0)
		{
			header('Location: rooms.php');
			die();
		}

		$db = new PdoDb();
		$db->beginTransaction();

		$req = $db->prepare('INSERT INTO `rooms` (`title`, `owner`, `money`) VALUES (:title, :owner, :money);');
		$req->bindParam(':title', $title, PDO::PARAM_STR);
		$req->bindParam(':owner', $owner, PDO::PARAM_INT);
		$req->bindParam(':money', $money, PDO::PARAM_INT);
		$req->execute();

		$id = 0;
		$req = $db->prepare('SELECT `id` FROM `rooms` ORDER BY `id` DESC;');
		$req->execute();
		while (list($roomid) = $req->fetch(PDO::FETCH_NUM)) {
			$id = $roomid;
			break;
		}

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
		header('Location: rooms.php');
	}
?>