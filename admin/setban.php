<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		echo 'false';
		die();
	}
	else
	{
		$ban = intval($_GET['ban']);
		$userid = intval($_GET['id']);

		$db = new PdoDb();
		$req = $db->prepare('UPDATE `users` SET `ban`=:ban WHERE `id`=:userid;');
		$req->bindParam(':ban', $ban, PDO::PARAM_INT);
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();

		echo 'true';
	}
?>