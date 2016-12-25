<?php
	require_once('utils.php');

	if (!isUserHasAccess(3 /* Manage Users */) ) {
		header('Location: 403.php');
		die();
	}

	$adminSelfUsers = false;
	if (isUserAdmin() && isset($_GET['self']) && $_GET['self'] == 'true') {
		$adminSelfUsers = true;
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
			<div class="panel-heading">User Types</div>

			<table class="table">
				<thead>
					<tr>
						<th>User Type</th>
						<th>Parent User Type</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `name`, `parent` FROM `usertypes` ORDER BY `id`;');
						$req->execute();

						while (list($id, $usertype, $parent) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td>
										<a href="usertype.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($usertype); ?></a>
									</td>
									<td>
										<?php 
											if ($parent > 1 /* administrator */) {
												?>
													<a href="usertype.php?id=<?php echo $parent; ?>"><?php echo htmlspecialchars(getUserTypeName($parent)); ?></a>
												<?php
											} else {
												echo 'have not';
											}
										?>
									</td>
									<td>
										<?php if ($id > 1 /* administrator */) { ?>
											<form method="POST" action="dodeleteusertype.php">
												<input type="submit" class="red-submit" value="Delete">
												<input type="hidden" name="id" value="<?php echo $id; ?>">
											</form>
										<?php } else { ?>
											non-removable type
										<?php } ?>
									</td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

		<form method="POST" action="doaddusertype.php">
			<div class="admin-content panel panel-default">
				<div class="panel-heading">Add user type</div>
					<table class="table">
						<tbody>
							<tr>
								<td>Title</td>
								<td>
									<input type="text" name="title">
								</td>
							</tr>
							<tr>
								<td>Parent</td>
								<td>
									<select name="parent">
										<?php
											$req = $db->prepare('SELECT `id`, `name` FROM `usertypes` ORDER BY `id`;');
											$req->execute();

											while (list($id, $usertype) = $req->fetch(PDO::FETCH_NUM)) {
												?>
													<option value="<?php echo $id;?>"><?php echo $usertype; ?></option>
												<?php
											}
										?>
									</select>
								</td>
								<tr>
									<td colspan="2">
										<input type="submit" value="Submit">
									</td>
								</tr>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>

		<?php require_once('footer.php'); ?>
	</body>
</html>