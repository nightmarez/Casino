<?php
	require_once('utils.php');

	if (!isUserHasAccess(10 /* Rooms */)) {
		header('Location: 403.php');
		die();
	}

	if (!isset($_POST['id'])) {
		header('Location: rooms.php');
		die();
	}

	$id = intval($_POST['id']);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Edit Room</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Edit Room "<?php echo getRoomTitle($id); ?>"</div>

			<table class="table">
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `owner`, `money` FROM `rooms` WHERE `id`=:id AND `deleted`=0 ORDER BY `id`;');
						$req->bindParam(':id', $id, PDO::PARAM_INT);
						$req->execute();

						while (list($owner, $money) = $req->fetch(PDO::FETCH_NUM)) {
							?>
								<tr>
									<td>Manager</td>
									<td>
										<select id="manager">
											<?php
												$db1 = new PdoDb();
												$req1 = $db->prepare('SELECT `id`, `fullname` FROM `users` WHERE `ban`=0 AND `usertype`=2;');
												$req1->execute();

												while (list($managerid, $fullname) = $req1->fetch(PDO::FETCH_NUM))
												{
													?>
														<option value="<?php echo $managerid; ?>" <?php if ($managerid == $owner) { echo 'selected'; } ?>><?php echo $fullname; ?></option>
													<?php
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Chief</td>
									<td>
										<a id="chief" href="user.php?id=<?php echo getUserParent($owner); ?>"><?php echo getUserName(getUserParent($owner)); ?></a>
									</td>
								</tr>
								<tr>
									<td>Money</td>
									<td><?php echo intval($money); ?></td>
								</tr>
							<?php
							break;
						}
					?>

					<tr>
						<td colspan="2">
							<form action="addmoneytoroom.php" method="POST" style="float: left;">
								<input type="submit" value="Add Money">
								<input type="hidden" name="id" value="<?php echo $id; ?>">
							</form>

							<form action="getmoneyfromroom.php" method="POST" style="float: left; margin-left: 10px;">
								<input type="submit" value="Get Money">
								<input type="hidden" name="id" value="<?php echo $id; ?>">
							</form>

							<form action="dodeleteroom.php" method="POST" style="float: left; margin-left: 10px;">
								<input type="submit" class="red-submit" value="Delete">
								<input type="hidden" name="id" value="<?php echo $id; ?>">
							</form>
						</td>
					</tr>
				</tbody>
			</table>

			<script>
				$(document).ready(function() {
					$('#manager').change(function() {
						$.get('dochangeroommanager.php?id=' + <?php echo $id; ?> + '&managerid=' + parseInt($(this).val()), function(result) {
							if (result.trim() == 'false') {
								alertify.error('Some error happens');
							} else {
								alertify.success('Manager successfully updated');

								$('#chief').attr('href', 'user.php?id=' + parseInt(result.split('|')[0]));
								$('#chief').text(result.split('|')[1]);
							}
						});
					});
				});
			</script>
		</div>
	</body>
</html>