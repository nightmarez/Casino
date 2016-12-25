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

	if (!isset($_GET['id'])) {
		$id = getUserId();
	} else {
		$id = intval($_GET['id']);
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
			<div class="panel-heading">User</div>

			<?php
				$db = new PdoDb();
				$req = $db->prepare('SELECT `id`, `login`, `usertype`, `fullname`, `ban`, `parent` FROM `users` WHERE `id`=:id;');
				$req->bindParam(':id', $id, PDO::PARAM_INT);
				$req->execute();
				$currid = getUserId();
							
				while (list($id, $login, $usertype, $fullname, $ban, $parent) = $req->fetch(PDO::FETCH_NUM))
				{
			?>

				<table class="table">
					<tbody>
						<tr>
							<td>Login</td>
							<td><?php echo htmlspecialchars($login); ?></td>
						</tr>
						<tr>
							<td>User Type</td>
							<td><a href="usertype.php?id=<?php echo $usertype; ?>"><?php echo getUserTypeName($usertype); ?></a></td>
						</tr>
						<tr>
							<td>Full Name</td>
							<td><?php echo htmlspecialchars($fullname); ?></td>
						</tr>
						<tr>
							<td>Chief</td>
							<td>
								<?php
									if ($parent == 0) {
								?>
									has not
								<?php
									} else {
								?>
									<a href="user.php?id=<?php echo $parent; ?>"><?php echo getUserName($parent); ?></a>
								<?php
									}
								?>
							</td>
						</tr>
						<tr>
							<td>Actions</td>
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
							</td>
						</tr>
					</tbody>
				</table>

			<?php
					break;
				}
			?>
		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>