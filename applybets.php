<?php
	if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']))
	{
		echo 0;
		die();
	}

	$login = htmlspecialchars($_COOKIE['login']);
	$pass = htmlspecialchars($_COOKIE['pass']);

	require_once('utils.php');

	$request = json_decode($_POST['request']);
	$gameid = $request->{'gameid'};
	$token = $request->{'token'};
	$data = $request->{'data'};

	// =================================================================================================================

	$totalBets = 0;
	for ($i = 0; $i < count($data); ++$i)
	{
		$totalBets += $data[$i]->{'bet'};
	}

	// =================================================================================================================

	$totalMoney = 0;
	$userId = 0;
	$db = new PdoDb();
	$req = $db->prepare('SELECT * FROM `users` WHERE `login`=:login AND `pass`=:pass AND `level`=2;');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':pass', $pass, PDO::PARAM_STR);
	$req->execute();

	while (list($id, $login, $pass, $level, $activated, $sms, $money) = $req->fetch(PDO::FETCH_NUM))
	{
		$totalMoney = $money;
		$userId = $id;
		break;
	}

	// =================================================================================================================

	if ($totalBets > $totalMoney)
	{
		echo 'error: not enough money';
		die();
	}

	// =================================================================================================================

	$totalMoney -= $totalBets;
	$req = $db->prepare('UPDATE `users` SET `money`=:totalMoney WHERE `id`=:userId;');
	$req->bindParam(':totalMoney', $totalMoney, PDO::PARAM_INT);
	$req->bindParam(':userId', $userId, PDO::PARAM_INT);
	$req->execute();

	// =================================================================================================================

	for ($i = 0; $i < count($data); ++$i)
	{
		$bet = $data[$i]->{'bet'};
		$balls = implode(',', $data[$i]->{'balls'});
		$req = $db->prepare('INSERT INTO `bets` (`token`, `gameid`, `bet`, `balls`) VALUES (:token, :gameid, :bet, :balls);');
		$req->bindParam(':token', $token, PDO::PARAM_STR);
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->bindParam(':bet', $bet, PDO::PARAM_INT);
		$req->bindParam(':balls', $balls, PDO::PARAM_STR);
		$req->execute();
	}

	// =================================================================================================================

	echo 'OK';
?>