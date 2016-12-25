<?php
	require_once('utils.php');

	if (!isUserHasAccess(15 /* Products */)) {
		header('Location: 403.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Storage</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Storage</div>

			<table class="table">
				<thead>
					<tr>
						<th>Icon</th>
						<th>Title</th>
						<th>Cost</th>
						<th>Count</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `title`, `cost`, `count` FROM `products` WHERE `count` > 0;');
						$req->execute();

						while (list($id, $title, $cost, $count) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td>
										<div style="width: 100px; height: 100px; background-repeat: no-repeat; background-size: contain; background-image: url('products/<?php echo $id; ?>.jpg');"></div>
									</td>
									<td><?php echo $title; ?></td>
									<td><?php echo $cost; ?></td>
									<td><?php echo $count; ?></td>
									<td colspan="2">
										<form action="buyproduct.php" method="POST" style="float: left; margin-left: 0;">
											<input type="submit" value="Buy">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
											<input type="hidden" name="back" value="storage">
										</form>
										<form action="dothrowproduct.php" method="POST" style="float: left; margin-left: 10px;">
											<input type="submit" class="red-submit" value="Throw">
											<input type="hidden" name="id" value="<?php echo $id; ?>">
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