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
		<div class="panel-heading">Настройки</div>

		<table class="table">
			<tbody>
				<tr>
					<td>Чат</td>
					<td>
						<?php
							$chatEnabled = isChatEnabled();
						?>

						<select id="chat">
							<option value="1" <?php echo $chatEnabled == 1 ? 'selected' : ''; ?>>Включён</option>
							<option value="0" <?php echo $chatEnabled == 0 ? 'selected' : ''; ?>>Отключён</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Отображать номер спина</td>
					<td>
						<?php
							$spinVisible = isSpinNumberVisible();
						?>

						<select id="spin">
							<option value="1" <?php echo $spinVisible == 1 ? 'selected' : ''; ?>>Отображать</option>
							<option value="0" <?php echo $spinVisible == 0 ? 'selected' : ''; ?>>Не отображать</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Отладочный режим</td>
					<td>
						<?php
							$debugMode = isDebugMode();
						?>

						<select id="debug">
							<option value="1" <?php echo $debugMode == 1 ? 'selected' : ''; ?>>Включён</option>
							<option value="0" <?php echo $debugMode == 0 ? 'selected' : ''; ?>>Отключён</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Сбросить состояние генератора случайных чисел</td>
					<td>
						<input id="rnd" type="button" value="Сбросить">
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<script>
		$(document).ready(function() {
			$('#chat').change(function() {
				$.get('/admin/setchatenabled.php?value=' + parseInt($(this).val()), function(val) {
					if (val.trim() == 'true') {
						alertify.success('Изменения сохранены');
					} else {
						alertify.error('Не удалось сохранить изменения'); 
					}
				});
			});

			$('#spin').change(function() {
				$.get('/admin/setspinvisible.php?value=' + parseInt($(this).val()), function(val) {
					if (val.trim() == 'true') {
						alertify.success('Изменения сохранены');
					} else {
						alertify.error('Не удалось сохранить изменения'); 
					}
				});
			});

			$('#debug').change(function() {
				$.get('/admin/setdebugmode.php?value=' + parseInt($(this).val()), function(val) {
					if (val.trim().indexOf('true') != -1) {
						alertify.success('Изменения сохранены');
						location.reload();
					} else {
						alertify.error('Не удалось сохранить изменения');
					}
				});
			});

			$('#rnd').click(function() {
				$.get('/common/php/resetrnd.php', function(val) {
					if (val.trim() == 'true') {
						alertify.success('Изменения сохранены');
					} else {
						alertify.error('Не удалось сохранить изменения');
					}
				});
			});
		});
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>