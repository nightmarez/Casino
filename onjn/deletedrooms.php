<?php
	require_once('utils.php');

	if (!isUserHasAccess(10 /* Rooms */) ) {
		header('Location: 403.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Deleted Rooms</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Rooms</div>

			<table class="table">
				<thead>
					<tr>
						<th>Room</th>
						<th>Manager</th>
						<th>Chief</th>
						<th>Money</th>
						<th>Places</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `title`, `owner`, `money` FROM `rooms` WHERE `deleted`=1 ORDER BY `id`;');
						$req->execute();

						$currid = getUserId();

						while (list($id, $title, $owner, $money) = $req->fetch(PDO::FETCH_NUM)) {
							if (!isUserParentOf($currid, $owner)) {
								continue;
							}

							?>
								<tr>
									<td><a href="room.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($title); ?></a></td>
									<td>
										<a href="user.php?id=<?php echo $owner; ?>"><?php echo getUserName($owner); ?></a>
									</td>
									<td>
										<a href="user.php?id=<?php echo getUserParent($owner); ?>"><?php echo getUserName(getUserParent($owner)); ?></a>
									</td>
									<td><?php echo intval($money); ?></td>
									<td>
										<?php
											$req1 = $db->prepare('SELECT COUNT(*) FROM `places` WHERE `room`=:room ORDER BY `id`;');
											$req1->bindParam(':room', $id, PDO::PARAM_INT);
											$req1->execute();
											echo $req1->fetch(PDO::FETCH_NUM)[0];
										?>
									</td>
									<td>
									<form action="dorestoreroom.php" method="POST" style="float: left;">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
											<input type="submit" value="Restore" style="margin-left: 0 !important;">
										</form>
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