<?php
	require_once('utils.php');

	if (!isUserHasAccess(16 /* Clients */)) {
		header('Location: 403.php');
		die();
	}

	$vip = false;

	if (isset($_GET['vip']) && $_GET['vip'] == 'true') {
		$vip = true;
	}

	$banned = false;

	if (isset($_GET['banned']) && $_GET['banned'] == 'true') {
		$banned = true;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php if ($banned) { echo 'Banned '; } ?>Clients Accounting <?php if ($vip && !$banned) { echo 'VIP'; } ?></title>
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
			<div class="panel-heading"><?php if ($banned) { echo 'Banned '; } ?>Clients Accounting <?php if ($vip && !$banned) { echo '<span style="color: red;">VIP</span>'; } ?></div>

			<table class="table">
				<thead>
					<tr>
						<th>Full Name</th>
						<th>Card Number</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `name`, `banned`, `vip` FROM `clients`;');
						$req->execute();
									
						while (list($id, $name, $bannedclient, $vipclient) = $req->fetch(PDO::FETCH_NUM))
						{
							if ($vip == 1 && $vipclient == 0) {
								if ($banned == 0) {
									continue;
								}
							}

							if ($banned == 1 && $bannedclient == 0 || $banned == 0 && $bannedclient == 1) {
								continue;
							}

							?>
								<tr>
									<td>
										<a <?php if ($vipclient == 1) { echo 'class="vip-client"'; } ?> href="client.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></a>
									</td>
									<td>
										<?php
											$card = $id . '';

											while (strlen($card) < 8) {
												$card = '0' . $card;
											}
										?>

										<a href="card.php?id=<?php echo $card; ?>"><?php echo htmlspecialchars($card); ?></a>
									</td>
									<td>
										<?php if ($banned == 0) { ?>
											<form action="editclient.php" method="POST" style="float: left;">
												<input type="submit" value="Edit">
												<input type="hidden" name="id" value="<?php echo $id; ?>">
											</form>
											<form action="dodeleteclient.php" method="POST" style="float: left; margin-left: 10px;">
												<input type="submit" class="red-submit" value="Ban">
												<input type="hidden" name="id" value="<?php echo $id; ?>">
											</form>
										<?php } else { ?>
											<form action="dorestoreclient.php" method="POST" style="float: left; margin-left: 10px;">
												<input type="submit" value="Restore">
												<input type="hidden" name="id" value="<?php echo $id; ?>">
											</form>
										<?php } ?>
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