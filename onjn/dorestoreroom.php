<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(10 /* Manage Rooms */)) {
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

		$req = $db->prepare('UPDATE `rooms` SET `deleted`=0 WHERE `id`=:id');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		//$req = $db->prepare('UPDATE `rooms` SET `deleted`=0 WHERE `parent`=:id');
		//$req->bindParam(':id', $id, PDO::PARAM_INT);
		//$req->execute();

		$db->commit();

		if ($adminSelfUsers) {
			header('Location: rooms.php?self=true');
		} else {
			header('Location: rooms.php');
		}
	}
?>