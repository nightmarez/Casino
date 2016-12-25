<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(3 /* Manage Users */)) {
		header('Location: 403.php');
		die();
	}

	$adminSelfUsers = false;
	if (isUserAdmin() && isset($_POST['self']) && $_POST['self'] == 'true') {
		$adminSelfUsers = true;
	}

	if (!isset($_POST['id'])) {
		if ($adminSelfUsers) {
			header('Location: users.php?self=true');
		} else {
			header('Location: users.php');
		}
	}

	$id = intval($_POST['id']);

	if ($id != getUserId() && !isUserChildOf($id, getUserId())) {
		if ($adminSelfUsers) {
			header('Location: users.php?self=true');
		} else {
			header('Location: users.php');
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Edit User</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Edit User</div>

			<?php
				$db = new PdoDb();
				$req = $db->prepare('SELECT `login`, `fullname` FROM `users` WHERE `id`=:id;');
				$req->bindParam(':id', $id, PDO::PARAM_INT);
				$req->execute();

				while (list($login, $fullname) = $req->fetch(PDO::FETCH_NUM))
				{
			?>

				<form action="doedituser.php" method="POST">
					<table class="table">
						<tbody>
							<tr>
								<td>Login</td>
								<td>
									<input type="text" name="login" value="<?php echo htmlspecialchars($login); ?>"></input>
								</td>
							</tr>
							<tr>
								<td>Full Name</td>
								<td>
									<input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>">
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="hidden" name="id" value="<?php echo $id; ?>">
									<input type="submit" value="Save">
								</td>
							</tr>
						</tbody>
					</table>
				</form>

			<?php
					break;
				}
			?>
		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>