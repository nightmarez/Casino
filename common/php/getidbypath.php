<?php
	require_once('../../utils.php');

	if (!isset($_GET['path'])) {
		die();
	}

	$path = htmlspecialchars($_GET['path']);

	$db = new PdoDb();
	$req = $db->prepare('SELECT `id` FROM `programs` WHERE `path`=:path;');
	$req->bindParam(':path', $path, PDO::PARAM_STR);
	$req->execute();
	$any = false;

	while (list($id) = $req->fetch(PDO::FETCH_NUM))
	{
		echo $id;
		$any = true;
		break;
	}

	if (!$any) {
		echo 'false';
	}
?>