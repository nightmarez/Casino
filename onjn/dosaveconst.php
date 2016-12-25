<?php
	require_once('../utils.php');

	if (isUserUnregistered()) {
		echo 'false';
		die();
	} 
	else if (!isUserHasAccess(13 /* Languages */)) {
		echo 'false';
		die();
	}
	else
	{
		$id = intval(htmlspecialchars($_GET['id']));
		$title = htmlspecialchars($_GET['title']);

		if (strlen($title) <= 0)
		{
			echo 'false';
			die();
		}

		$req = $db->prepare('UPDATE `consts` SET `title`=:title WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->bindParam(':title', $title, PDO::PARAM_STR);
		$req->execute();

		echo 'true';
	}
?>