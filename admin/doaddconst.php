<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		echo 'false';
		die();
	}
	else
	{
		$title = htmlspecialchars($_GET['title']);

		if (strlen($title) <= 0)
		{
			echo 'false';
			die();
		}

		$db = new PdoDb();
		$req = $db->prepare('INSERT INTO `consts` (`title`) VALUES (:title);');
		$req->bindParam(':title', $title, PDO::PARAM_STR);
		$req->execute();
		echo 'true';
	}
?>