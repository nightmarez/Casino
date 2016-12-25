<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_POST['id'])) {
			die();
		}

		$id = intval($_POST['id']);

		if (!isset($_POST['gameid'])) {
			die();
		}

		$gameid = intval($_POST['gameid']);

		// delete symbols
		$db = new PdoDb();
		$db->beginTransaction();
		$req = $db->prepare('DELETE FROM `symbols` WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		// need to recalculate symbols names

		// collect items for this games
		$items = array();
		$req = $db->prepare('SELECT `id`, `name` FROM `symbols` WHERE `gameid`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();
		while (list($itemid, $itemname) = $req->fetch(PDO::FETCH_NUM)) {
			$items[] = array($itemid, $itemname);
		}

		// resort
		$symbols = array('aa', 'bb', 'cc', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii', 'jj', 'kk', 'll', 'mm', 'nn', 'oo', 'pp', 'qq', 'rr', 'ss', 'tt', 'uu', 'vv', 'ww', 'xx', 'yy', 'zz');
		$items2 = array();
		foreach ($items as $item) {
			$items2[] = array($item[0], array_shift($symbols));
		}

		// update symbols names
		foreach ($items2 as $item) {
			$req = $db->prepare('UPDATE `symbols` SET `name`=:itemname WHERE `id`=:itemid');
			$itemid = $item[0];
			$itemname = $item[1];
			$req->bindParam(':itemid', $itemid, PDO::PARAM_INT);
			$req->bindParam(':itemname', $itemname, PDO::PARAM_STR);
			echo 'id: ' . $itemid . ' , name: ' . $itemname;
			$req->execute();
		}

		$db->commit();
		header('Location: /admin/symbols.php?gameid=' . $gameid);
	}
?>
