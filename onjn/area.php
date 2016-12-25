<?php
	require_once('utils.php');

	if (!isUserHasAccess(7 /* Areas */)) {
		header('Location: 403.php');
		die();
	}

	$adminSelfUsers = false;
	if (isUserAdmin() && isset($_GET['self']) && $_GET['self'] == 'true') {
		$adminSelfUsers = true;
	}

	if (!isset($_GET['id'])) {
		header('Location: 403.php');
		die();
	}

	$id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Area</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">User</div>

			<?php
				$db = new PdoDb();
				$req = $db->prepare('SELECT `id`, `title` FROM `areas` WHERE `id`=:id;');
				$req->bindParam(':id', $id, PDO::PARAM_INT);
				$req->execute();
							
				while (list($id, $title) = $req->fetch(PDO::FETCH_NUM))
				{
			?>

				<table class="table">
					<tbody>
						<tr>
							<td>Title</td>
							<td><?php echo htmlspecialchars($title); ?></td>
						</tr>
						<tr>
							<td>Has Access</td>
							<td>
								<?php
									$req1 = $db->prepare('SELECT `id`, `usertype`, `fullname` FROM `users`;');
									$req1->execute();
									$hasAccess = array();

									while (list($userid, $usertype, $fullname) = $req1->fetch(PDO::FETCH_NUM))
									{
										if (isUserHasAccess($id, $userid)) {
											$hasAccess[] = '<a href="user.php?id=' . $userid . '">' . $fullname . '</a>&nbsp;(' . getUserTypeName($usertype) . ')';
										}
									}

									if (count($hasAccess) > 0) {
										echo implode(', &nbsp;', $hasAccess);
									}
								?>
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