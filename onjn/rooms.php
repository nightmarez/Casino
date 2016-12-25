<?php
	require_once('utils.php');

	if (!isUserHasAccess(10 /* Rooms */) ) {
		header('Location: 403.php');
		die();
	}

	$adminSelfRooms = false;
	if (isUserAdmin() && isset($_GET['self']) && $_GET['self'] == 'true') {
		$adminSelfRooms = true;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Users</title>
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
						$req = $db->prepare('SELECT `id`, `title`, `owner`, `money` FROM `rooms` WHERE `deleted`=0 ORDER BY `id`;');
						$req->execute();

						$currid = getUserId();

						while (list($id, $title, $owner, $money) = $req->fetch(PDO::FETCH_NUM)) {
							if ($adminSelfRooms && !isUserParentOf($currid, $owner)) {
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
										<form action="editroom.php" method="POST" style="float: left; margin-left: 0;">
											<input type="submit" value="Edit">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
										</form>
										
										<form action="addmoneytoroom.php" method="POST" style="float: left; margin-left: 10px;">
											<input type="submit" value="Add Money">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
										</form>

										<form action="getmoneyfromroom.php" method="POST" style="float: left; margin-left: 10px;">
											<input type="submit" value="Get Money">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
										</form>

										<form action="dodeleteroom.php" method="POST" style="float: left; margin-left: 10px;">
											<input type="submit" class="red-submit" value="Delete">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
										</form>
									</td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

		<form method="POST" action="doaddroom.php">
			<div class="admin-content panel panel-default">
				<div class="panel-heading">Add Room</div>
					<table class="table">
						<tbody>
							<tr>
								<td>Room Title</td>
								<td>
									<input type="text" name="title">
								</td>
							</tr>
							<tr>
								<td>Owner (manager)</td>
								<td>
									<select name="owner">
									<?php
											$req = $db->prepare('SELECT `id`, `fullname`, `usertype` FROM `users` ORDER BY `id`;');
											$req->execute();

											while (list($id, $fullname, $usertype) = $req->fetch(PDO::FETCH_NUM)) {
												if ($usertype != 2 /* manager */) {
													continue;
												}

												?>
													<option value="<?php echo $id; ?>"><?php echo htmlspecialchars($fullname); ?></option>
												<?php
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Money</td>
								<td>
									<input type="number" name="money" value="10000">
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="submit" value="Submit">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>

		<?php require_once('footer.php'); ?>
	</body>
</html>