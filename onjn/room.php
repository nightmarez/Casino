<?php
	require_once('utils.php');

	if (!isUserHasAccess(10 /* Rooms */)) {
		header('Location: 403.php');
		die();
	}

	$adminSelfUsers = false;
	if (isUserAdmin() && isset($_GET['self']) && $_GET['self'] == 'true') {
		$adminSelfUsers = true;
	}

	if (!isset($_GET['id'])) {
		header('Location: rooms.php');
		die();
	}

	$id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Room</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Room "<?php echo getRoomTitle($id); ?>"</div>

			<table class="table">
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `owner`, `money` FROM `rooms` WHERE `id`=:id ORDER BY `id`;');
						$req->bindParam(':id', $id, PDO::PARAM_INT);
						$req->execute();

						while (list($owner, $money) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td>Manager</td>
									<td>
										<a href="user.php?id=<?php echo $owner; ?>"><?php echo getUserName($owner); ?></a>
									</td>
								</tr>
								<tr>
									<td>Chief</td>
									<td>
										<a href="user.php?id=<?php echo getUserParent($owner); ?>"><?php echo getUserName(getUserParent($owner)); ?></a>
									</td>
								</tr>
								<tr>
									<td>Money</td>
									<td><?php echo intval($money); ?></td>
								</tr>
							<?php
							break;
						}
					?>
					<tr>
						<td colspan="2">
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
				</tbody>
			</table>
		</div>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Places</div>

			<table class="table">
				<thead>
					<tr>
						<th>Title</th>
						<th>Is Empty</th>
						<th>Money</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `title`, `free`, `money` FROM `places` WHERE `room`=:room ORDER BY `title`;');
						$req->bindParam(':room', $id, PDO::PARAM_INT);
						$req->execute();

						while (list($placeid, $title, $free, $money) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td><?php echo htmlspecialchars($title); ?></td>
									<td>
										<?php
											if ($free == 1) {
												?><span style="color: green; font-weight: bold;">empty</span><?php
											} else if ($free == 0) {
												?><span style="color: red; font-weight: bold;">busy</span><?php
											} else if ($free == 2) {
												?><span style="color: blue; font-weight: bold;">booked</span><?php
											}
										?>
									</td>
									<td><?php echo $money; ?></td>
									<td>
										<?php if ($free == 0 /* busy */) { ?>
										<form style="float: left;" action="dotakeplacemoney.php" method="POST">
											<input type="submit" value="Take money">
											<input type="hidden" name="id" value="<?php echo $placeid; ?>">
										</form>
										<?php } else if ($free == 1 /* empty */ || $free == 2 /* booked */) { ?>
										<form style="float: left;" action="dofillplace.php" method="POST">
											<input type="submit" value="Fill place">
											<input type="hidden" name="id" value="<?php echo $placeid; ?>">
										</form>
											<?php if ($free == 1 /* empty */) { ?>
												<form style="float: left;" action="dobookplace.php" method="POST">
													<input type="submit" value="Book place">
													<input type="hidden" name="id" value="<?php echo $placeid; ?>">
												</form>
												<form style="float: left;" action="dodeleteplace.php" method="POST">
													<input type="submit" class="red-submit" value="Remove place">
													<input type="hidden" name="id" value="<?php echo $placeid; ?>">
												</form>
											<?php } ?>
											<?php if ($free == 2 /* booked */) { ?>
												<form style="float: left;" action="dounbookplace.php" method="POST">
													<input type="submit" value="Unbook place">
													<input type="hidden" name="id" value="<?php echo $placeid; ?>">
												</form>
											<?php } ?>
										<?php } ?>
									</td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Add Place</div>

			<form action="doaddplace.php" method="POST" style="margin-left: 0;">
				<table class="table">
					<tbody>
						<tr>
							<td>Title</td>
							<td><input type="text" name="title"></td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="submit" value="Add">
								<input type="hidden" name="id" value="<?php echo $id; ?>">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>

		<script>
			$(document).ready(function() {
				_.each($('.navbar-nav').find('li'), function(li) {
					li = $(li);

					_.each(li.find('a'), function(a) {
						a = $(a);

						if (a.attr('href') == 'index.php') {
							a.attr('href', 'index.php?roomid=<?php echo $id; ?>');
						}
					});
				});
			});
		</script>

		<?php require_once('footer.php'); ?>
	</body>
</html>