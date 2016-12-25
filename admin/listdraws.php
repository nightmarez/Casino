<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		die();
	}
	else
	{
		$db = new PdoDb();
		$req = $db->prepare('SELECT `id` FROM `games` ORDER BY `id`;');
		$req->execute();
		$arr = array();

		while (list($id) = $req->fetch(PDO::FETCH_NUM))
		{
			$arr[] = array($id, $id);
		}

		echo json_encode($arr);
	}
?>