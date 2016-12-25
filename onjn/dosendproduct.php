<?php
	require_once('utils.php');

	if (!isset($_GET['roomid'])) {
		echo '-1';
		die();
	}

	if (!isset($_GET['placeid'])) {
		echo '-1';
		die();
	}

	if (!isset($_GET['productid'])) {
		echo '-1';
		die();
	}

	$roomid = intval($_GET['roomid']);
	$placeid = intval($_GET['placeid']);
	$productid = intval($_GET['productid']);

	$db = new PdoDb();
	$db->beginTransaction();

	$req = $db->prepare('SELECT `count`, `cost` FROM `products` WHERE `id`=:productid AND `count` > 0;');
	$req->bindParam(':productid', $productid, PDO::PARAM_INT);
	$req->execute();

	$count = 0;
	$cost = 0;
	
	while (list($c1, $c2) = $req->fetch(PDO::FETCH_NUM)) {
		$count = $c1;
		$cost = $c2;
	}

	if ($count <= 0) {
		echo '-1';
		die();
	}

	--$count;
	$req = $db->prepare('UPDATE `products` SET `count`=:count WHERE `id`=:productid;');
	$req->bindParam(':count', $count, PDO::PARAM_INT);
	$req->bindParam(':productid', $productid, PDO::PARAM_INT);
	$req->execute();

	$req = $db->prepare('UPDATE `places` SET `money`=`money`+:cost WHERE `id`=:placeid;');
	$req->bindParam(':cost', $cost, PDO::PARAM_INT);
	$req->bindParam(':placeid', $placeid, PDO::PARAM_INT);
	$req->execute();

	$req = $db->prepare('INSERT INTO `logs` (userid, action, item, room, money) VALUES (:userid, :action, :item, :room, :totalprice);');
	$userid = getUserId();
	$action = 'send';
	$req->bindParam(':userid', $userid, PDO::PARAM_INT);
	$req->bindParam(':action', $action, PDO::PARAM_STR);
	$req->bindParam(':item', $productid, PDO::PARAM_INT);
	$req->bindParam(':room', $roomid, PDO::PARAM_INT);
	$req->bindParam(':totalprice', $cost, PDO::PARAM_INT);
	$req->execute();

	$db->commit();
	echo $count;
?>