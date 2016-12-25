<?php
	require_once('utils.php');

	if (!isUserHasAccess(15 /* Products */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_POST['id'])) {
		if (isset($_POST['back'])) {
			header('Location: ' . $_POST['back'] . '.php');
		} else {
			header('Location: storage.php');
		}

		die();
	}

	$id = intval($_POST['id']);

	if (!isset($_POST['count'])) {
		if (isset($_POST['back'])) {
			header('Location: ' . $_POST['back'] . '.php');
		} else {
			header('Location: storage.php');
		}

		die();
	}

	$count = intval($_POST['count']);

	if (!isset($_POST['price'])) {
		if (isset($_POST['back'])) {
			header('Location: ' . $_POST['back'] . '.php');
		} else {
			header('Location: storage.php');
		}

		die();
	}

	$price = intval($_POST['price']);

	if (!isset($_POST['roomid'])) {
		if (isset($_POST['back'])) {
			header('Location: ' . $_POST['back'] . '.php');
		} else {
			header('Location: storage.php');
		}

		die();
	}

	$roomid = intval($_POST['roomid']);

	$db = new PdoDb();
	$db->beginTransaction();

	$totalprice = $price * $count;
	$money = 0;

	$req = $db->prepare('SELECT `money` FROM `rooms` WHERE `id`=:roomid;');
	$req->bindParam(':roomid', $roomid, PDO::PARAM_INT);
	$req->execute();
	while (list($m) = $req->fetch(PDO::FETCH_NUM)) {
		$money = $m;
		break;
	}

	if ($money < $totalprice) {
		$db->rollBack();

		if (isset($_POST['back'])) {
			header('Location: ' . $_POST['back'] . '.php');
		} else {
			header('Location: storage.php');
		}

		die();
	}

	$req = $db->prepare('UPDATE `products` SET `count`=`count`+:cnt WHERE `id`=:id;');
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->bindParam(':cnt', $count, PDO::PARAM_INT);
	$req->execute();

	$req = $db->prepare('UPDATE `rooms` SET `money`=`money`-:totalprice WHERE `id`=:roomid;');
	$req->bindParam(':totalprice', $totalprice, PDO::PARAM_INT);
	$req->bindParam(':roomid', $roomid, PDO::PARAM_INT);
	$req->execute();

	$req = $db->prepare('INSERT INTO `logs` (userid, action, item, room, money) VALUES (:userid, :action, :item, :room, :totalprice);');
	$userid = getUserId();
	$action = 'buy';
	$req->bindParam(':userid', $userid, PDO::PARAM_INT);
	$req->bindParam(':action', $action, PDO::PARAM_STR);
	$req->bindParam(':item', $id, PDO::PARAM_INT);
	$req->bindParam(':room', $roomid, PDO::PARAM_INT);
	$req->bindParam(':totalprice', $totalprice, PDO::PARAM_INT);
	$req->execute();

	$db->commit();

	if (isset($_POST['back'])) {
		header('Location: ' . $_POST['back'] . '.php');
	} else {
		header('Location: storage.php');
	}
?>