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
		<div class="panel-heading">Пополнение баланса</div>

		<table class="table">
			<tbody>
				<tr>
					<td>Номер счёта</td>
					<td>
						<input type="text" id="bill">
					</td>
				</tr>
				<tr>
					<td>Сумма</td>
					<td>
						<input type="text" id="money" value="0">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p>
							<a href="#" class="btn btn-primary" role="button" id="apply">Пополнить баланс</a>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<script>
		$(document).ready(function() {
			$('#apply').click(function(e) {
				var money = parseInt($('#money').val().trim());

				if (isNaN(money) || !money) {
					alertify.error('Введена неправильная сумма');
				} else {
					var bill = parseInt($('#bill').val().trim());

					if (isNaN(bill)) {
						alertify.error('Введён неправильный номер счёта');
					} else {
						$.get('/admin/userexists.php?id=' + bill, function(result) {
							if (result.trim() == 'true') {
								$.get('/admin/doaddmoney.php?id=' + bill + '&money=' + money, function(result) {
									if (result.trim() == 'true') {
										alertify.success('Счёт пополнен');
										$('#bill').val('');
										$('#money').val('0');
									} else {
										alertify.error('Произошла ошибка при пополнении счёта');
									}
								});
							} else {
								alertify.error('Указанный счёт не существует');
							}
						});
					}
				}

				return false;
			});
		});
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>