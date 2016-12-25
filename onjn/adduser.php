<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(3 /* Add Users */)) {
		header('Location: 403.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Add User</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Add User</div>

			<form action="doadduser.php" method="POST">
				<table class="table">
					<tbody>
						<tr>
							<td>Login</td>
							<td>
								<input type="text" name="login"></input>
							</td>
						</tr>
						<tr>
							<td>Password</td>
							<td>
								<input type="password" name="pass"></input>
							</td>
						</tr>
						<tr>
							<td>User Type</td>
							<td>
								<select name="usertype">
									<?php
										$req = $db->prepare('SELECT `id`, `name` FROM `usertypes` ORDER BY `id`;');
										$req->execute();
										$currtype = getUserType();

										while (list($id, $usertype) = $req->fetch(PDO::FETCH_NUM)) {
											if (isTypeParentOf($id, $currtype)) {
												continue;
											}

											?>
												<option value="<?php echo $id;?>"><?php echo $usertype; ?></option>
											<?php
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Full Name</td>
							<td>
								<input type="text" name="fullname">
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="submit" value="Add User">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>