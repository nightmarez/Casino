<?php
	require_once('utils.php');

	if (!isUserHasAccess(1 /* All Users */) && !isUserHasAccess(2 /* Self Users */)) {
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
			<div class="panel-heading">Users</div>

			<table class="table">
				<thead>
					<tr>
						<th>Login</th>
						<th>User Type</th>
						<th>Full Name</th>
						<th>Chief</th>
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
							if ($ban == 1) {
								continue;
							}

							if ($adminSelfUsers && !isUserParentOf($currid, $id)) {
								continue;
							}

							?>
								<tr>
									<td>
										<a href="user.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($login); ?></a>
									</td>
									<td>
										<a href="usertype.php?id=<?php echo $usertype; ?>"><?php echo htmlspecialchars(getUserTypeName($usertype)); ?></a>
									</td>
									<td>
										<a href="user.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($fullname); ?></a>
									</td>
									<td>
										<?php
											if ($parent == 0) {
										?>
											has not
										<?php
											} else {
										?>
											<a href="user.php?id=<?php echo $parent; ?>"><?php echo getUserName($parent); ?></a>&nbsp;(<a href="usertype.php?id=<?php echo getUserType($parent); ?>"><?php echo getUserTypeName(getUserType($parent)); ?></a>)
										<?php
											}
										?>
									</td>
									<td>
										<?php
											$canShowEditButton = isUserParentOf($currid, $id) || $currid == $id;
										?>

										<?php if ($canShowEditButton) { ?>
											<form action="edituser.php" method="POST" style="float: left;">
												<input type="submit" value="Edit">
												<input type="hidden" name="id" value="<?php echo $id; ?>">
											</form>
										<?php } ?>

										<?php
											$canShowDeleteButton = isUserParentOf($currid, $id);
										?>

										<?php if ($canShowDeleteButton) { ?>
											<form action="dodeleteuser.php" method="POST" style="float: left; margin-left: 10px;">
												<input type="submit" class="red-submit" value="Delete">

												<?php if ($adminSelfUsers) { ?>
													<input type="hidden" name="self" value="true">
												<?php } ?>

												<input type="hidden" name="id" value="<?php echo $id; ?>">
											</form>
										<?php } ?>

										<?php
											if (!$canShowEditButton && !$canShowDeleteButton) {
												if ($usertype == 1 /* Administrator */) {
													echo 'you have not permission to modify other administrator';
												} else {
													echo 'user have other owner';
												}
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