<?php
	require_once('utils.php');

	if (!isUserHasAccess(16 /* Clients */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_GET['id'])) {
		header('Location: cards.php');
		die();
	}

	$id = intval($_GET['id']);

	if ($id <= 0) {
		header('Location: cards.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Card</title>
		<?php require_once('head.php'); ?>

		<style>
			a.vip-client, a:visited.vip-client, a:hover.vip-client {
				color: red;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Card</div>

			<?php
				$vipcard = 0;
			?>

			<table class="table">
				<tbody>
					<tr>
						<td>Card</td>
						<td>
							<?php
								$card = $id . '';

								while (strlen($card) < 8) {
									$card = '0' . $card;
								}

								echo $card;
							?>
						</td>
					</tr>
					<tr>
						<td>Holder</td>
						<td>
							<?php
								$found = false;

								$db = new PdoDb();
								$req = $db->prepare('SELECT `id`, `name`, `vip` FROM `clients`;');
								$req->execute();
											
								while (list($cid, $holder, $vip) = $req->fetch(PDO::FETCH_NUM))
								{
									if ($cid == $id) {
										$found = true;
										$vipcard = $vip;

										?>
											<a <?php if ($vip == 1) { echo 'class="vip-client"'; } ?> href="client.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($holder); ?></a>
										<?php

										break;
									}
								}

								if (!$found) {
									echo 'unknown';
								}
							?>
						</td>
					</tr>
					<tr>
						<td>VIP</td>
						<td><?php if ($vipcard == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>