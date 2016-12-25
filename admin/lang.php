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

	<?php
		$langid = intval($_GET['id']);
		$langname = '';

		$db = new PdoDb();
		$req = $db->prepare('SELECT `title` FROM `langs` WHERE `id`=:langid;');
		$req->bindParam(':langid', $langid, PDO::PARAM_INT);
		$req->execute();

		while (list($title) = $req->fetch(PDO::FETCH_NUM))
		{
			$langname = $title;
			break;
		}
	?>

	<div class="panel panel-default">
		<div class="panel-heading">Язык (<?php echo $langname; ?>)</div>

		<table class="table">
			<thead>
				<tr>
					<th>Константа</th>
					<th>Перевод</th>
					<th>Действие</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$db = new PdoDb();
					$req = $db->prepare('SELECT t1.id, t1.title, t2.text FROM `consts` AS t1 LEFT OUTER JOIN `l10n` AS t2 ON t1.id = t2.const AND t2.lang=:langid;');
					$req->bindParam(':langid', $langid, PDO::PARAM_INT);
					$req->execute();

					while (list($id, $const, $text) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							<tr>
								<td><?php echo htmlspecialchars($const); ?></td>
								<td>
									<input type="text" id="text-<?php echo $id; ?>" value="<?php echo htmlspecialchars($text); ?>">
								</td>
								<td>
									<a href="#" class="btn btn-primary save-button" role="button" id="save-button-<?php echo $id; ?>">Сохранить</a>
								</td>
							</tr>
						<?php
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td><a href="#" class="btn btn-primary" role="button" id="save-button-all">Сохранить всё</a></td>
				</tr>
			</tfoot>
		</table>
	</div>

	<script>
		$(document).ready(function() {
			$('.save-button').click(function() {
				var btn = $(this);
				var id = btn.attr('id').split('-');
				id = id[id.length - 1];
				var text = $('#text-' + id).val();

				$.get('/admin/dosavetext.php?lang=<?php echo $langid; ?>&const=' + id + '&text=' + text, function(result) {
					if (result.trim() == 'true') {
						alertify.success('Значение сохранено');
					} else {
						alertify.error('Произошла ошибка при сохранении значения');
					}
				});
			});

			$('#save-button-all').click(function() {
				var btns = [];
				var ok = true;

				$('.save-button').each(function(index, value) {
					btns.push($(value));
				});

				var doneFunc = function() {
					if (ok) {
						alertify.success('Значения сохранены');
					} else {
						alertify.error('Произошла ошибка при сохранении значений');
					}
				};

				var saveFunc = function() {
					var btn = btns.pop();
					var id = btn.attr('id').split('-');
					id = id[id.length - 1];
					var text = $('#text-' + id).val();

					$.get('/admin/dosavetext.php?lang=<?php echo $langid; ?>&const=' + id + '&text=' + text, function(result) {
						if (result.trim() == 'true') {
							if (btns.length) {
								saveFunc();
							} else {
								doneFunc();
							}
						} else {
							ok = false;
							doneFunc();
						}
					});
				};

				if (btns.length) {
					saveFunc();
				}
			});
		});
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>