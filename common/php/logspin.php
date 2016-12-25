<?php
	require_once('../../utils.php');

	if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass']) || !isset($_POST['spin']) || !isset($_POST['win']) || !isset($_POST['gameid']) ||
		!isset($_POST['bet']) || !isset($_POST['betlines']) || !isset($_POST['matrix']) || !isset($_POST['balance']) || !isset($_POST['id']) || !isset($_POST['token']))
	{
		die();
	}

	$login = htmlspecialchars($_COOKIE['login']);
	$pass = htmlspecialchars($_COOKIE['pass']);

	$db = new PdoDb();
	$req = $db->prepare('SELECT `id` FROM `users` WHERE `login`=:login AND `pass`=:pass;');
	$req->bindParam(':login', $login, PDO::PARAM_STR);
	$req->bindParam(':pass', $pass, PDO::PARAM_STR);
	$req->execute();
	$any = false;
	$userid = false;

	while (list($id) = $req->fetch(PDO::FETCH_NUM))
	{
		$userid = $id;
		$any = true;
		break;
	}

	if (!$any) {
		die();
	}

	$gameid = intval($_POST['gameid']);
	$spin = htmlspecialchars($_POST['spin']);
	$win = intval($_POST['win']);
	$bet = intval($_POST['bet']);
	$betlines = intval($_POST['betlines']);
	$matrix = htmlspecialchars($_POST['matrix']);
	$balance = intval($_POST['balance']);

	$id = intval($_POST['id']);
	$token = htmlspecialchars($_POST['token']);

	$req = $db->prepare('UPDATE `pgames` SET `user`=:userid, `gameid`=:gameid, `bet`=:bet, `betlines`=:betlines, `matrix`=:matrix, `spin`=:spin, `win`=:win, `time`=NOW(), `money`=:balance WHERE `id`=:id AND `guid`=:token;');
	$req->bindParam(':userid', $userid, PDO::PARAM_INT);
	$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
	$req->bindParam(':bet', $bet, PDO::PARAM_INT);
	$req->bindParam(':betlines', $betlines, PDO::PARAM_INT);
	$req->bindParam(':matrix', $matrix, PDO::PARAM_STR);
	$req->bindParam(':spin', $spin, PDO::PARAM_STR);
	$req->bindParam(':win', $win, PDO::PARAM_INT);
	$req->bindParam(':balance', $balance, PDO::PARAM_INT);
	$req->bindParam(':id', $id, PDO::PARAM_INT);
	$req->bindParam(':token', $token, PDO::PARAM_STR);
	$req->execute();
?>