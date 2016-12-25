<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		echo 'FALSE';
		die();
	}

	if (!isUserHasAccess(6 /* Users Access */)) {
		echo 'FALSE';
		die();
	}

	if (!isset($_GET['usertype']) || !isset($_GET['area']) || !isset($_GET['access'])) {
		die();
	}

	$usertype = intval($_GET['usertype']);
	$area = intval($_GET['area']);
	$access = intval($_GET['access']);

	$db = new PdoDb();
	$req = $db->prepare('INSERT INTO `accessmatrix` (`usertype`, `area`, `access`) VALUES (:usertype, :area, :access) ON DUPLICATE KEY UPDATE `access`=:access;');
	$req->bindParam(':usertype', $usertype, PDO::PARAM_INT);
	$req->bindParam(':area', $area, PDO::PARAM_INT);
	$req->bindParam(':access', $access, PDO::PARAM_INT);
	$req->execute();

	echo 'OK';
?>