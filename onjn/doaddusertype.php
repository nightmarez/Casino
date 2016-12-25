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
		if (!isset($_POST['title']) || !isset($_POST['parent'])) {
			header('Location: usertypes.php');
			die();
		}

		$title = htmlspecialchars($_POST['title']);

		if (strlen($title) <= 0) {
			header('Location: usertypes.php');
			die();
		}

		$parent = intval($_POST['parent']);

		$db = new PdoDb();
		$req = $db->prepare('INSERT INTO `usertypes` (`name`, `parent`) VALUES (:title, :parent);');
		$req->bindParam(':title', $title, PDO::PARAM_STR);
		$req->bindParam(':parent', $parent, PDO::PARAM_STR);
		$req->execute();

		header('Location: usertypes.php');
	}
?>