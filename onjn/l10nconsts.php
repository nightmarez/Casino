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
			<div class="panel-heading"><?php echo getL10n('Admin-Constants'); ?></div>

			<table class="table">
				<thead>
					<tr>
						<th>id</th>
						<th>Константа</th>
						<th>Действие</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$db = new PdoDb();
						$req = $db->prepare('SELECT `id`, `title` FROM `consts`;');
						$req->execute();

						while (list($id, $title) = $req->fetch(PDO::FETCH_NUM))
						{
							?>
								<tr>
									<td><?php echo $id; ?></td>
									<td>
										<input type="text" value="<?php echo htmlspecialchars($title); ?>" id="const-<?php echo $id; ?>">
									</td>
									<td>
										<a href="#" class="btn btn-primary save-button" role="button" id="save-button-<?php echo $id; ?>"><?php echo getL10n('Save'); ?></a>
										<a href="#" class="btn btn-primary delete-button" role="button" id="delete-button-<?php echo $id; ?>"><?php echo getL10n('Delete'); ?></a>
									</td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading"><?php echo getL10n('Consts-AddConst'); ?></div>

			<table class="table">
				<tbody>
					<tr>
						<td><?php echo getL10n('Consts-Const'); ?></td>
						<td><input type="text" id="title"></td>
					</tr>
					<tr>
						<td colspan="2">
							<p>
								<a href="#" class="btn btn-primary" role="button" id="apply"><?php echo getL10n('Add'); ?></a>
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

					$.get('doaddconst.php?title=' + title, function(result) {
						if (result.trim() == 'true') {
							alertify.success('<?php echo getL10n("Consts-ConstAdded"); ?>');
							location.href = 'l10nconsts.php';
						} else {
							alertify.error('<?php echo getL10n("Message-CouldNotSave"); ?>');
						}
					});

					return false;
				});

				$('.save-button').click(function() {
					var btn = $(this);
					var id = btn.attr('id').split('-');
					id = id[id.length - 1];
					var title = $('#const-' + id).val();

					$.get('dosaveconst.php?id=' + id + '&title=' + title, function(result) {
						if (result.trim() == 'true') {
							alertify.success('<?php echo getL10n("Message-ChangesSaved"); ?>');
						} else {
							alertify.error('<?php echo getL10n("Message-ValueCouldntDeleted"); ?>');
						}
					});
				});

				$('.delete-button').click(function() {
					var btn = $(this);
					var id = btn.attr('id').split('-');
					id = id[id.length - 1];

					$.get('dodeleteconst.php?id=' + id, function(result) {
						if (result.trim() == 'true') {
							alertify.success('<?php echo getL10n("Message-ValueDeleted"); ?>');
							location.href = 'l10nconsts.php';
						} else {
							alertify.error('<?php echo getL10n("Message-ValueCouldntDeleted"); ?>');
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