<?php
	require_once('utils.php');

	function generateGameToken()
	{
		$str = '1234567890abcdefghijklmnopqrstuvwxyz';
		$result = '';
		$len = strlen($str);

		for ($i = 0; $i < $len; ++$i)
		{
			$idx = rand(0, $len);
			$result .= $str[$idx];
		}

		return $result;
	}

	function generateGame($userid)
	{
		$userid = intval($userid);
		updateUserActivity($userid);

		$db = new PdoDb();
		$db->beginTransaction();

		$token = generateGameToken();
		$req = $db->prepare('INSERT INTO `games` (`token`, `user`) VALUES (:token, :userid);');
		$req->bindParam(':token', $token, PDO::PARAM_STR);
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();

		$req = $db->prepare('SELECT * FROM `games` WHERE `token`=:token AND `user`=:userid ORDER BY `id` DESC LIMIT 0, 1;');
		$req->bindParam(':token', $token, PDO::PARAM_STR);
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		
		$req->execute();

		while (list($id) = $req->fetch(PDO::FETCH_NUM))
		{
			$db->commit();
			return $id . '|' . $token;
		}

		$db->commit();
		return false;
	}

	function lastUnplayed($userid)
	{
		$db = new PdoDb();
		$req = $db->prepare('SELECT * FROM `games` WHERE `user`=:userid AND LENGTH(TRIM(`balls`)) = 0 ORDER BY `id` DESC LIMIT 0, 1;');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();

		while (list($id, $token) = $req->fetch(PDO::FETCH_NUM))
		{
			return $id . '|' . $token;
		}

		return false;
	}

	function genBalls($count)
	{
		$arr = array();
		$result = array();

		for ($i = 1; $i <= 80; ++$i)
		{
			$arr[] = $i;
		}

		for ($j = 0; $j < $count; ++$j)
		{
			$rnd = rand(0, count($arr) - 1);
			$result[] = $arr[$rnd];
			
			$tmp = array();
			for ($k = 0; $k < count($arr); ++$k)
			{
				if ($k != $rnd)
				{
					$tmp[] = $arr[$k];
				}
			}
			$arr = $tmp;
		}

		return $result;
	}

	function playGame($userid, $token)
	{
		$userid = intval($userid);
		updateUserActivity($userid);

		$last = lastUnplayed($userid);
		$gameid = explode('|', $last)[0];
		$currToken = explode('|', $last)[1];

		if ($token != $currToken)
		{
			return false;
		}

		$db = new PdoDb();
		$db->beginTransaction();

		$gameballs = genBalls(20);
		$bls = implode(',', $gameballs);
		$req = $db->prepare('UPDATE `games` SET `balls`=:bls WHERE `id`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->bindParam(':bls', $bls, PDO::PARAM_STR);
		$req->execute();

		// =================================================================================================================

		$table = array(
			array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			array(3, 1, 0, 0, 0, 0, 0, 0, 0, 0),
			array(0, 10, 2, 1, 1, 0, 0, 0, 0, 0),
			array(0, 0, 45, 10, 3, 2, 2, 0, 0, 0),
			array(0, 0, 0, 80, 20, 15, 4, 5, 2, 0),
			array(0, 0, 0, 0, 150, 60, 20, 15, 10, 5),
			array(0, 0, 0, 0, 0, 500, 80, 50, 25, 30),
			array(0, 0, 0, 0, 0, 0, 1000, 200, 125, 100),
			array(0, 0, 0, 0, 0, 0, 0, 2000, 1000, 300),
			array(0, 0, 0, 0, 0, 0, 0, 0, 5000, 2000),
			array(0, 0, 0, 0, 0, 0, 0, 0, 0, 10000)
		);

		$req = $db->prepare('SELECT * FROM `bets` WHERE `token`=:token AND `gameid`=:gameid;');
		$req->bindParam(':token', $token, PDO::PARAM_INT);
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();

		$win = 0;
		while (list($id, $token, $currgameid, $bet, $balls) = $req->fetch(PDO::FETCH_NUM))
		{
			$balls = explode(',', $balls);
			$total = 0;

			for ($i = 0; $i < count($balls); ++$i)
			{
				$ball = $balls[$i];

				for ($j = 0; $j < count($gameballs); ++$j)
				{
					$gameball = $gameballs[$j];

					if ($ball == $gameball)
					{
						++$total;
						break;
					}
				}
			}

			$win += $bet * $table[$total][count($balls) - 1];
		}

		// =================================================================================================================

		$req = $db->prepare('UPDATE `games` SET `win`=:win WHERE `id`=:gameid;');
		$req->bindParam(':win', $win, PDO::PARAM_INT);
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();

		// =================================================================================================================

		$req = $db->prepare('SELECT money FROM `users` WHERE `id`=:userid;');
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();

		$currentMoney = 0;
		while (list($money) = $req->fetch(PDO::FETCH_NUM))
		{
			$currentMoney = $money;
			break;
		}
		$currentMoney += $win;

		$req = $db->prepare('UPDATE `users` SET `money`=:currentMoney WHERE `id`=:userid;');
		$req->bindParam(':currentMoney', $currentMoney, PDO::PARAM_INT);
		$req->bindParam(':userid', $userid, PDO::PARAM_INT);
		$req->execute();

		$db->commit();

		// =================================================================================================================

		return [$win, $gameballs];
	}
?>