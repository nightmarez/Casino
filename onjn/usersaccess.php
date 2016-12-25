<?php
	require_once('utils.php');

	if (!isUserHasAccess(6 /* User Access */)) {
		header('Location: login.php');
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
		<title>Users Access Matrix</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Users Access Matrix</div>

			<table class="table">
				<thead>
					<tr>
						<th>User Type</th>
						<?php
							$db = new PdoDb();
							$req = $db->prepare('SELECT `id`, `title` FROM `areas` ORDER BY `id`;');
							$req->execute();

							while (list($id, $title) = $req->fetch(PDO::FETCH_NUM)) {
								?>
									<th>
										<a href="area.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($title); ?></a>
									</th>
								<?php
							}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `name` FROM `usertypes` ORDER BY `id`;');
						$req->execute();

						while (list($id, $usertype) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td>
										<a href="usertype.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($usertype); ?></a>
									</td>
									<?php
										$db1 = new PdoDb();
										$req1 = $db1->prepare('SELECT `id` FROM `areas` ORDER BY `id`;');
										$req1->execute();

										while (list($areaid) = $req1->fetch(PDO::FETCH_NUM)) {
											?>
												<td>
													<input id="checkbox-<?php echo $id; ?>-<?php echo $areaid; ?>" type="checkbox" <?php if (isUsertypeHasAccess($id, $areaid)) { echo 'checked'; } ?>>
												</td>
											<?php
										}
									?>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

		<script>
			$(document).ready(function() {
				$('input[type=checkbox]').change(function() {
					var value = $(this).prop('checked');
					var access = value ? 1 : 0;
					var usertype = parseInt($(this).prop('id').split('-')[1]);
					var area = parseInt($(this).prop('id').split('-')[2]);
					$.get('dochangeaccess.php?usertype=' + usertype + '&area=' + area + '&access=' + access, function(result) {
						if (result.trim() == 'OK') {
							alertify.success('Saved');
						} else {
							alertify.error('Some error happens');
						}
					}).error(function() {
						alertify.error('Some error happens');
					});
				})
			})
		</script>

		<?php require_once('footer.php'); ?>
	</body>
</html>