<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
		die();
	}

	if (!isUserHasAccess(7 /* Areas */)) {
		header('Location: 403.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Areas</title>
		<?php require_once('head.php'); ?>
		<style>
			a.comma:after {
				content: ',';
				margin-right: 5px;
			}

			a.comma:last-child:after {
				content: '';
				margin-right: 0;
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Areas</div>

			<table class="table">
				<thead>
					<tr>
						<th>Area title</th>
						<th>User types with access</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `title` FROM `areas` ORDER BY `id`;');
						$req->execute();

						while (list($id, $title) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td>
										<a href="area.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($title); ?></a>
									</td>
									<td>
										<?php
											$db1 = new PdoDb();
											$req1 = $db1->prepare('SELECT `usertype` FROM `accessmatrix` WHERE `area`=:id;');
											$req1->bindParam(':id', $id, PDO::PARAM_INT);
											$req1->execute();

											while (list($usertype) = $req1->fetch(PDO::FETCH_NUM)) {
												?>
													<a class="comma" href="usertype.php?id=<?php echo $usertype; ?>"><?php echo getUserTypeName($usertype); ?></a>
												<?php
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