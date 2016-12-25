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
		<title>Clients Accidents</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Clients Accidents</div>

			<table class="table">
				<thead>
					<tr>
						<th>Reporter</th>
						<th>Client</th>
						<th>Description</th>
						<th>Sanctions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `reporter`, `client`, `description`, `sanctions` FROM `accidents`;');
						$req->execute();
									
						while (list($id, $reporter, $client, $description, $sanctions) = $req->fetch(PDO::FETCH_NUM))
						{
							?>
								<tr>
									<td>
										<a href="user.php?id=<?php echo $reporter; ?>"><?php echo htmlspecialchars(getUserName($reporter)); ?></a>
									</td>
									<td>
										<a href="client.php?id=<?php echo $client; ?>"><?php echo htmlspecialchars(getClientName($client)); ?></a>
									</td>
									<td>
										<?php echo htmlspecialchars($description); ?>
									</td>
									<td>
										<?php
											if ($sanctions == 'banned') {
												?>
													<a href="clients.php?banned=true">banned</a>
												<?php
											} else {
												echo htmlspecialchars($sanctions);
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