<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['id'])) {
			die();
		}

		$id = intval($_GET['id']);

		if (!isset($_GET['game'])) {
			die();
		}

		$gameid = intval($_GET['game']);

		if (!isset($_GET['value'])) {
			die();
		}

		$value = htmlspecialchars($_GET['value']);

		// change symbol name
		$db = new PdoDb();
		$db->beginTransaction();
		$req = $db->prepare('UPDATE `symbols` SET `name`=:value WHERE `id`=:id;');
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->bindParam(':value', $value, PDO::PARAM_STR);
		$req->execute();

		// need to recalculate symbols names

		// collect items for this games
		$items = array();
		$req = $db->prepare('SELECT `id`, `name` FROM `symbols` WHERE `gameid`=:gameid ORDER BY `name`, `id`;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();
		while (list($itemid, $itemname) = $req->fetch(PDO::FETCH_NUM)) {
			$items[] = array($itemid, $itemname);
		}

		// resort
		$used = array();
		$symbols = array('aa', 'bb', 'cc', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii', 'jj', 'kk', 'll', 'mm', 'nn', 'oo', 'pp', 'qq', 'rr', 'ss', 'tt', 'uu', 'vv', 'ww', 'xx', 'yy', 'zz');

		if (in_array($value, $symbols)) {
			$symbols = array_flip($symbols);
			unset($symbols[$value]);
			$symbols = array_flip($symbols);
		}

		$items2 = array();
		foreach ($items as $item) {
			if ($item[0] == $id) {
				if (in_array($value, $used)) {
					$curr = $value;
					while (($curr = array_shift($symbols)) == $value || in_array($curr, $used)) { }
					$used[array_flip($used)[$value]][1] = $curr;
				}

				$items2[] = array($item[0], $value);
				$used[] = $value;
			} else {
				if (in_array($item[1], $used) || $item[1] == $value) {
					$curr = $item[1];
					while (($curr = array_shift($symbols)) == $value || in_array($curr, $used)) { }
					$items2[] = array($item[0], $curr);
					$used[] = $curr;
				} else {
					$items2[] = array($item[0], $item[1]);
					$used[] = $item[1];
				}
			}
		}

		// update symbols names
		foreach ($items2 as $item) {
			$req = $db->prepare('UPDATE `symbols` SET `name`=:itemname WHERE `id`=:itemid');
			$itemid = $item[0];
			$itemname = $item[1];
			$req->bindParam(':itemid', $itemid, PDO::PARAM_INT);
			$req->bindParam(':itemname', $itemname, PDO::PARAM_STR);
			$req->execute();
		}

		$db->commit();
		echo 'OK';
	}
?>