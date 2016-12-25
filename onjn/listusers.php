<?php
	require_once('utils.php');

	if (!isUserHasAccess(1 /* All Users */))
	{
		die();
	}
	else
	{
		$db = new PdoDb();
		$req = $db->prepare('SELECT `id`, `login` FROM `users` ORDER BY `login`;');
		$req->execute();
		$arr = array();

		while (list($id, $login) = $req->fetch(PDO::FETCH_NUM))
		{
			$arr[] = array($id, $login);
		}

		$req = $db->prepare('SELECT `id`, `fullname` FROM `users` ORDER BY `fullname`;');
		$req->execute();

		while (list($id, $fullname) = $req->fetch(PDO::FETCH_NUM))
		{
			$arr[] = array($id, $fullname);
		}

		echo json_encode($arr);
	}
?>