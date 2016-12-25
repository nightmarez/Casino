<?php
	require_once('utils.php');
	$adminSelfUsers = true;

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(4 /* Deleted Users */)) {
		header('Location: 403.php');
		die();
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
			<div class="panel-heading">Users</div>

			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Login</th>
						<th>Level</th>
						<th>Full Name</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `login`, `usertype`, `fullname`, `ban`, `parent` FROM `users`;');
						$req->execute();
						$currid = getUserId();
							
						while (list($id, $login, $usertype, $fullname, $ban, $parent) = $req->fetch(PDO::FETCH_NUM))
						{
							if ($ban == 0) {
								continue;
							}

							if ($adminSelfUsers && !isUserParentOf($currid, $id)) {
								continue;
							}

							?>
								<tr>
									<td><?php echo intval($id); ?></td>
									<td>
										<?php echo htmlspecialchars($login); ?>
									</td>
									<td>
										<?php echo htmlspecialchars(getUserTypeName($usertype)); ?>
									</td>
									<td>
										<?php echo htmlspecialchars($fullname); ?>
									</td>
									<td>
										<form action="dorestoreuser.php" method="POST" style="float: left;">
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