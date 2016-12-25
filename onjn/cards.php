<?php
	require_once('utils.php');

	if (!isUserHasAccess(16 /* Clients */)) {
		header('Location: 403.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Cards</title>
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
			<div class="panel-heading">Cards</div>

			<table class="table">
				<thead>
					<tr>
						<th>Card Number</th>
						<th>Card Holder</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `name`, `vip` FROM `clients`;');
						$req->execute();
									
						while (list($id, $holder, $vip) = $req->fetch(PDO::FETCH_NUM))
						{
							?>
								<tr>
									<td>
										<?php
											$card = $id . '';

											while (strlen($card) < 8) {
												$card = '0' . $card;
											}
										?>

										<a href="card.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($card); ?></a>
									</td>
									<td>
										<a <?php if ($vip == 1) { echo 'class="vip-client"'; } ?> href="client.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($holder); ?></a>
									</td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>

		<?php require_once('footer.php'); ?>
	</body>
</html>