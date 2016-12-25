<?php
	require_once('../utils.php');

	if (!adminZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
?>
	<?php require_once('header.php'); ?>

	<div class="panel panel-default">
		<div class="panel-heading">Языки</div>

		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th>Язык</th>
					<th>Действия</th>
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
									<a href="/admin/lang.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($title); ?></a>
								</td>
								<td>
									<input id="delete-<?php echo $id; ?>" class="delete-button" type="button" value="Удалить" />
								</td>
							</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">Добавить язык</div>

		<table class="table">
			<tbody>
				<tr>
					<td>Язык</td>
					<td><input type="text" id="title"></td>
				</tr>
				<tr>
					<td colspan="2">
						<p>
							<a href="#" class="btn btn-primary" role="button" id="apply">Добавить</a>
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

				$.get('/admin/doaddlang.php?title=' + title, function(result) {
					if (result.trim() == 'true') {
						alertify.success('Язык добавлен');
						location.href = '/admin/l10n.php';
					} else {
						alertify.error('Произошла ошибка при добавлении языка');
					}
				});

				return false;
			});

			$('.delete-button').click(function() {
				var id = parseInt($(this).attr('id').split('-')[1]);
				$.get('/admin/dodeletelang.php?id=' + id, function(result) {
					if (result.trim() == 'true') {
						alertify.success('Язык удалён');
						location.href = '/admin/l10n.php';
					} else {
						alertify.error('Произошла ошибка при удалении языка');
					}
				});
			});
		});
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>