<?php
	require_once('utils.php');

	if (!isUserHasAccess(12 /* Logs */)) {
		header('Location: 403.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Logs</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Logs</div>

			<table class="table">
				<thead>
					<tr>
						<th>Date</th>
						<th>User</th>
						<th>Room</th>
						<th>Action</th>
						<th>Item</th>
						<th>Money</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `userid`, `action`, `item`, `room`, `money`, `date` FROM `logs` ORDER BY `date` DESC;');
						$req->execute();

						while (list($id, $userid, $action, $item, $room, $money, $date) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td><?php echo $date; ?></td>
									<td>
										<a href="user.php?id=<?php echo $userid; ?>"><?php echo getUserName($userid); ?></a>
									</td>
									<td>
										<a href="room.php?id=<?php echo $room; ?>"><?php echo getRoomTitle($room); ?></a>
									</td>
									<td style="font-weight: bold; color: <?php if ($action == 'buy') { echo 'red'; } else if ($action == 'sell') { echo 'green'; } else if ($action == 'send') { echo '#FFA500;'; } else if ($action == 'add') { echo 'blue'; } else if ($action == 'get') { echo 'blue'; }?>;">
										<?php
											echo $action;
										?>
									</td>
									<td>
										<!--
										<a href="product.php?id=<?php echo $item; ?>">
										-->
											<?php echo getProductTitle($item); ?>
										<!--
										</a>
										-->
									</td>
									<td style="font-weight: bold; color: <?php if ($action == 'buy') { echo 'red'; } else if ($action == 'sell') { echo 'green'; } else if ($action == 'send') { echo '#FFA500;'; } else if ($action == 'add') { echo 'green'; } else if ($action == 'get') { echo 'red'; } ?>;">
										<?php
											if ($action == 'buy' || $action == 'get') {
												if ($money != 0 ) { echo '-' . $money; } else { echo $money; };
											} else if ($action == 'send') {
												echo $money;
											} else if ($action == 'sell' || $action == 'add') {
												if ($money != 0 ) { echo '+' . $money; } else { echo $money; };
											}
										?>
									</td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>
