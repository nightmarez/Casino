<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: usertypes.php');
		die();
	} 
	else if (!isUserHasAccess(5 /* User types */)) {
		header('Location: usertypes.php');
		die();
	}
	else
	{
		if (!isset($_POST['id'])) {
			header('Location: usertypes.php');
			die();
		}

		$id = intval($_POST['id']);

		$db = new PdoDb();
		$req = $db->prepare('DELETE FROM `usertypes` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_STR);
		$req->execute();

		header('Location: usertypes.php');
	}
?>