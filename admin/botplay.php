<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
		if (!isset($_GET['botid']))
		{
			die();
		}

		$botid = htmlspecialchars($_GET['botid']);

		if (!isset($_GET['gameid']))
		{
			die();
		}

		$gameid = intval($_GET['gameid']);

		if (!isset($_GET['presetid']))
		{
			die();
		}

		$presetid = htmlspecialchars($_GET['presetid']);

		$playCount = 1;

		if (isset($_GET['playcount'])) {
			$playCount = intval($_GET['playcount']);
		}

		for ($z = 0; $z < $playCount; ++$z)
		{
			$db = new PdoDb();
			$db->beginTransaction();

			// get bot id
			$req = $db->prepare('SELECT `id` FROM `users` WHERE `login`=:login;');
			$req->bindParam(':login', $botid, PDO::PARAM_INT);
			$req->execute();
			$userid = intval($req->fetch(PDO::FETCH_ASSOC)['id']);

			// get current preset id
			if (!isset($presetid) || !strlen($presetid))
			{
				$req = $db->prepare('SELECT `preset` FROM `programs` WHERE `id`=:id;');
				$req->bindParam(':id', $gameid, PDO::PARAM_STR);
				$req->execute();
				$presetid = htmlspecialchars($req->fetch(PDO::FETCH_ASSOC)['preset']);
			}

			// get current preset strings
			$req = $db->prepare('SELECT `preset` FROM `presets` WHERE `gameid`=:gameid AND `presetid`=:presetid ORDER BY `stringid`;');
			$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
			$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
			$req->execute();
			$arr = array();

			while (list($preset) = $req->fetch(PDO::FETCH_NUM))
			{
				$arr[] = explode(',', $preset);
			}

			// lines lengths
			$lengths = [];
			$minlen = 1000;
			for ($j = 0; $j < count($arr); ++$j) {
				$l = count($arr[$j]);
				$lengths[] = $l;

				if ($minlen > $l) {
					$minlen = $l;
				}
			}

			// uniq access token
			$guid = htmlspecialchars(uniqid('token_', true));

			// create record for current game
			$req = $db->prepare('INSERT INTO `pgames` (`guid`, `presetid`) VALUES (:guid, :presetid);');
			$req->bindParam(':guid', $guid, PDO::PARAM_STR);
			$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
			$req->execute();

			// get id for current game
			$req = $db->prepare('SELECT `id` FROM `pgames` WHERE `guid`=:guid;');
			$req->bindParam(':guid', $guid, PDO::PARAM_STR);
			$req->execute();
			$id = intval($req->fetch(PDO::FETCH_ASSOC)['id']);

			// get new spin
			require_once('rnd.php');
			$spin = [];
			$old = randomNumberGet($minlen);
			for ($j = 0; $j < 5 /* ??? */; ++$j) {
				$old = $old + randomNumberGet($minlen);
				$spin[] = $old;
			}

			// create result matrix
			$matrix = [];

			for ($i = 0; $i < 5; ++$i) {
				$matrix[] = [];
			}

			for ($i = 0; $i < 5; ++$i) {
				for ($j = 0; $j < 3; ++$j) {
					$pos = $spin[$i] + $j;

					if ($pos >= $lengths[$i]) {
						$pos = $pos % $lengths[$i];
					}

					$matrix[$i][] = str_replace("'", "", $arr[$i][$pos]);
				}
			}

			// win lines indices
			$winLinesIndices = array(
				array(1, 1, 1, 1, 1),
				array(0, 0, 0, 0, 0),
				array(2, 2, 2, 2, 2),
				array(0, 1, 2, 1, 0),
				array(2, 1, 0, 1, 2));

			// costs
			$costs = array(
				'gg' => array(0, 0, 5, 20, 50, 200),
				'cc' => array(0, 0, 0, 50, 200, 500),
				'ff' => array(0, 0, 0, 20, 50, 200),
				'bb' => array(0, 0, 0, 50, 200, 500),
				'ee' => array(0, 0, 0, 20, 50, 200),
				'dd' => array(0, 0, 0, 20, 50, 200),
				'aa' => array(0, 0, 0, 100, 1000, 5000),
				'hh' => array(0, 0, 0, 2, 10, 50)
			);

			// calculate win
			$totalWin = 0;

			// calculate wins by lines
			$lines = array();
			for ($i = 0; $i < count($winLinesIndices); ++$i) {
				$line = array();

				for ($j = 0; $j < count($matrix); ++$j) {
					$line[] = $matrix[$j][$winLinesIndices[$i][$j]];
				}

				$lines[] = $line;
			}

			for ($i = 0; $i < count($lines); ++$i) {
				$bet = 8 /*bet/line*/;
				$scatter = 'hh';
				$line = $lines[$i];
				$symbol = false;
				$count = 0;

				for ($j = 0; $j < count($line); ++$j) {
					$s = trim($line[$j]);

					if ($count == 0) {
						if ($s != $scatter) {
							$symbol = $s;
							++$count;
						} else {
							break;
						}
					} else {
						if ($s != $scatter && $s == $symbol) {
							++$count;
						} else {
							break;
						}
					}
				}

				if ($count > 0) {
					$totalWin += $bet * $costs[$symbol][$count];
				}
			}

			// calculate wins by scatters
			$scattersCount = 0;
			for ($i = 0; $i < count($matrix); ++$i) {
				for ($j = 0; $j < count($matrix[$i]); ++$j) {
					$symbol = $matrix[$i][$j];

					if ($symbol == 'hh' /* scatter */) {
						++$scattersCount;
					}
				}

				if ($scattersCount > 0) {
					$totalWin += 40 /*total bet*/ * $costs['hh'][$scattersCount];
				}
			}

			$req = $db->prepare('UPDATE `pgames` SET `user`=:userid, `gameid`=:gameid, `bet`=5, `betlines`=8, `matrix`="", `spin`="", `win`=:win, `time`=NOW(), `money`=1000000 WHERE `id`=:id AND `guid`=:token;');
			$req->bindParam(':userid', $userid, PDO::PARAM_INT);
			$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
			$req->bindParam(':win', $totalWin, PDO::PARAM_INT);
			$req->bindParam(':id', $id, PDO::PARAM_INT);
			$req->bindParam(':token', $guid, PDO::PARAM_STR);
			$req->execute();

			$db->commit();
		} // end one play

		echo 'OK';
	}
?>