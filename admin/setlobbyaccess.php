<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		echo 'false';
		die();
	}
	else
	{
		$userid = intval($_GET['id']);
		$ban = intval($_GET['lobbyaccess']);

		$db = new PdoDb();
		$req = $db->prepare('UPDATE `users` SET `lobbyaccess`=:ban WHERE `id`=:userid;');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();

		echo 'true';
	}
?>