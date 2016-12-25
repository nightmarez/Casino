<?php
	require_once('utils.php');

	if (!isUserHasAccess(3 /* Manage Users */) ) {
		header('Location: login.php');
		die();
	}

	if (!isset($_GET['id'])) {
		header('Location: usertypes.php');
		die();
	}

	$id = intval($_GET['id']);
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
			<div class="panel-heading">User Type: <?php echo getUserTypeName($id); ?></div>

			<table class="table">
				<tbody>
					<tr>
						<td>
							Change title
						</td>
						<td>
							<form action="dochangetypetitle.php" method="POST">
								<input type="text" name="title">
								<input type="hidden" name="id" value="<?php echo $id; ?>">
								<input type="submit" value="Change">
							</form>
						</td>
					</tr>
					<tr>
						<td>
							Change type
						</td>
						<td>
							<form action="dochangetypeparent.php" method="POST">
								<select name="parent">
									<?php
										$db = new PdoDb();
										$req = $db->prepare('SELECT `id`, `name` FROM `usertypes` WHERE `id`<>:self;');
										$req->bindParam(':self', $id, PDO::PARAM_INT);
										$req->execute();
										
										while (list($typeid, $usertype) = $req->fetch(PDO::FETCH_NUM)) {
											?>
												<option value="<?php echo $typeid; ?>"><?php echo $usertype; ?></option>
											<?php
										}
									?>
								</select>
								<input type="submit" value="Change">
							</form>
						</td>
					</tr>
					<tr>
						<td>
							Delete user type
						</td>
						<td>
							<form action="dodeleteusertype.php" method="POST">
								<input type="checkbox" checked>&nbsp; with all subtypes &nbsp;
								<input type="hidden" name="id" value="<?php echo $id; ?>">
								<input type="submit" class="red-submit" style="margin-left: 0;" value="Delete">
							</form>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="admin-content panel panel-default">
			<div class="panel-heading"><?php echo getUserTypeName($id); ?> parents</div>

			<table class="table">
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `name` FROM `usertypes`;');
						$req->execute();
						
						while (list($typeid, $usertype) = $req->fetch(PDO::FETCH_NUM)) {
							if (isTypeParentOf($typeid, $id)) {
								?>
									<tr>
										<td>
											<a href="usertype.php?id=<?php echo $typeid; ?>"><?php echo htmlspecialchars($usertype); ?></a>
										</td>
									</tr>
								<?php
							}
						}
					?>
				</tbody>
			</table>
		</div>

		<div class="admin-content panel panel-default">
			<div class="panel-heading"><?php echo getUserTypeName($id); ?> childs</div>

			<table class="table">
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `name` FROM `usertypes`;');
						$req->execute();
						
						while (list($typeid, $usertype) = $req->fetch(PDO::FETCH_NUM)) {
							if (isTypeChildOf($typeid, $id)) {
								?>
									<tr>
										<td>
											<a href="usertype.php?id=<?php echo $typeid; ?>"><?php echo htmlspecialchars($usertype); ?></a>
										</td>
									</tr>
								<?php
							}
						}
					?>
				</tbody>
			</table>
		</div>

		<?php require_once('footer.php'); ?>
	</body>
</html>