<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(3 /* Add/Delete Users */)) {
		header('Location: 403.php');
		die();
	}

	$adminSelfUsers = false;
	if (isUserAdmin() && isset($_POST['self']) && $_POST['self'] == 'true') {
		$adminSelfUsers = true;
	}

	if (!isset($_POST['id'])) {
		if ($adminSelfUsers) {
			header('Location: users.php?self=true');
		} else {
			header('Location: users.php');
		}
	}

	$id = intval($_POST['id']);

	if (!isUserParentOf(getUserId(), $id)) {
		if ($adminSelfUsers) {
			header('Location: users.php?self=true');
		} else {
			header('Location: users.php');
		}
	} else {
		$db = new PdoDb();
		$db->beginTransaction();

		$req = $db->prepare('UPDATE `users` SET `ban`=1 WHERE `id`=:id');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		$req = $db->prepare('INSERT INTO `managementlogs` (userid, action, target, date) VALUES (:userid, :action, :target, NOW());');
		$userid = getUserId();
		$action = 'fired';
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->bindParam(':action', $action, PDO::PARAM_STR);
		$req->bindParam(':target', $id, PDO::PARAM_INT);
		$req->execute();

		$db->commit();

		if ($adminSelfUsers) {
			header('Location: users.php?self=true');
		} else {
			header('Location: users.php');
		}
	}
?>