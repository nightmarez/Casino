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
		<title>Management Logs</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Management Logs</div>

			<table class="table">
				<thead>
					<tr>
						<th>Date</th>
						<th>Manager</th>
						<th>User</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `userid`, `action`, `target`, `date` FROM `managementlogs` ORDER BY `date` DESC;');
						$req->execute();

						while (list($id, $userid, $action, $target, $date) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td><?php echo $date; ?></td>
									<td>
										<a href="user.php?id=<?php echo $userid; ?>"><?php echo getUserName($userid); ?></a>
									</td>
									<td>
										<a href="user.php?id=<?php echo $target; ?>"><?php echo getUserName($target); ?></a>
									</td>
									<td style="font-weight: bold; color: <?php if ($action == 'fired') { echo 'red'; } else if ($action == 'hired') { echo 'green'; } else if ($action == 'restored') { echo '#FFA500'; } ?>;">
										<?php
											echo htmlspecialchars($action);
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
