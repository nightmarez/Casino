<?php
	require_once('utils.php');

	if (!isUserHasAccess(13 /* Languages */)) { 
		header('Location: 403.php');
		die();
	}
	else
	{
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Users</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<div class="panel panel-default">
			<div class="panel-heading">Languages</div>

			<table class="table">
				<thead>
					<tr>
						<th>id</th>
						<th>Language</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `title` FROM `langs`;');
						$req->execute();

						while (list($id, $title) = $req->fetch(PDO::FETCH_NUM))
						{
							?>
								<tr>
									<td><?php echo $id; ?></td>
									<td>
										<a href="lang.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($title); ?></a>
									</td>
									<td>
										<input id="delete-<?php echo $id; ?>" class="delete-button" type="button" value="Delete" />
									</td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">Add Language</div>

			<table class="table">
				<tbody>
					<tr>
						<td>Language</td>
						<td><input type="text" id="title"></td>
					</tr>
					<tr>
						<td colspan="2">
							<p>
								<a href="#" class="btn btn-primary" role="button" id="apply">Add</a>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<script>
			$(document).ready(function() {
				$('#apply').click(function() {
					var title = $('#title').val();

					$.get('doaddlang.php?title=' + title, function(result) {
						if (result.trim() == 'true') {
							alertify.success('Language added');
							location.href = 'l10n.php';
						} else {
							alertify.error('Some error happens');
						}
					});

					return false;
				});

				$('.delete-button').click(function() {
					var id = parseInt($(this).attr('id').split('-')[1]);
					$.get('dodeletelang.php?id=' + id, function(result) {
						if (result.trim() == 'true') {
							alertify.success('Language deleted');
							location.href = 'l10n.php';
						} else {
							alertify.error('Some error happens');
						}
					});
				});
			});
		</script>

		<?php require_once('footer.php'); ?>
	<?php
		}
	?>
	</body>
</html>