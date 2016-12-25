<?php
	require_once('utils.php');

	if (!isUserHasAccess(16 /* Clients */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_GET['id'])) {
		header('Location: clients.php');
		die();
	}

	$id = intval($_GET['id']);

	if ($id <= 0) {
		header('Location: clients.php');
		die();
	}

	$fullname = getClientName($id);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Client <?php echo htmlspecialchars($fullname); ?></title>
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
			<div class="panel-heading">Client <b><?php echo htmlspecialchars($fullname); ?></b></div>
			<table class="table">
				<?php
					$db = new PdoDb();
					$req = $db->prepare('SELECT `name`, `banned`, `vip` FROM `clients` WHERE `id`=:id;');
					$req->bindParam(':id', $id, PDO::PARAM_INT);
					$req->execute();
									
					while (list($name, $banned, $vip) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							<tr>
								<td>Full Name</td>
								<td><?php echo htmlspecialchars($name); ?></td>
							</tr>
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
								<td>VIP</td>
								<td><?php if ($vip == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
							</tr>
							<tr>
								<td>Banned</td>
								<td><?php if ($banned == 1) { echo 'Yes'; } else { echo 'No'; } ?></td>
							</tr>
							<tr>
								<td colspan="2">
									<form action="editclient.php" method="POST" style="float: left;">
										<input type="submit" value="Edit">
										<input type="hidden" name="id" value="<?php echo $id; ?>">
									</form>
									<form action="dodeleteclient.php" method="POST" style="float: left; margin-left: 10px;">
										<input type="submit" class="red-submit" value="Ban">
										<input type="hidden" name="id" value="1">
									</form>
								</td>
							</tr>
						<?php

						break;
					}
				?>
			</table>
		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>