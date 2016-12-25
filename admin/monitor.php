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
		<div class="panel-heading"><?php echo getL10n('Admin-Monitoring'); ?></div>

		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th><?php echo getL10n('Monitor-Header-Login'); ?></th>
					<th><?php echo getL10n('Monitor-Header-Status'); ?></th>
					<th><?php echo getL10n('Monitor-Header-LastConnection'); ?></th>
					<th><?php echo getL10n('Monitor-Header-LastPayment'); ?></th>
					<th><?php echo getL10n('Monitor-Header-Balance'); ?></th>
					<th><?php echo getL10n('Monitor-Header-InOut'); ?></th>
					<th><?php echo getL10n('Monitor-Header-Bonuses'); ?></th>
					<th><?php echo getL10n('Monitor-Header-Rating'); ?></th>
					<th><?php echo getL10n('Monitor-Header-MaxWin'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$db = new PdoDb();
					$req = $db->prepare('SELECT t1.id, t1.login, t1.pass, t1.level, t1.activated, t1.sms, t1.money, t1.ban, t2.last FROM `users` AS t1, `activity` AS t2 WHERE t1.id = t2.userid ORDER BY t2.last DESC;');
					$req->execute();
						
					while (list($id, $login, $pass, $level, $activated, $sms, $money, $ban, $last) = $req->fetch(PDO::FETCH_NUM))
					{
						if ($level == 1) {
							continue;
						}

						?>
						<tr>
							<td><a href="/admin/user.php?id=<?php echo $id; ?>"><?php echo $id; ?></a></td>
							<td><a href="/admin/user.php?id=<?php echo $id; ?>"><?php echo htmlentities($login); ?></a></td>
							<td>
								<select class="ban-select">
									<option value="0" <?php echo $ban == 0 ? 'selected' : ''; ?>><?php echo getL10n('Working'); ?></option>
									<option value="1" <?php echo $ban == 1 ? 'selected' : ''; ?>><?php echo getL10n('Blocked'); ?></option>
								</select>
							</td>
							<td><?php echo $last; ?></td>
							<td>
								<?php
									$db1 = new PdoDb();
									$req1 = $db->prepare('SELECT * FROM `payments` WHERE `userid`=:userid ORDER BY `id` LIMIT 0, 1;');
									$req1->bindParam(':userid', $userid, PDO::PARAM_INT);
									$req1->execute();
									$anyPayment = false;

									while (list($pid, $puserid, $pmoney) = $req1->fetch(PDO::FETCH_NUM))
									{
										$anyPayment = true;
										?>
											<a href="/admin/payments.php?id=<?php echo $id; ?>"><?php echo $pmoney; ?></a>
										<?php
										break;
									}

									if (!$anyPayment)
									{
										echo getL10n('NoData');
									}
								?>
							</td>
							<td><?php echo $money; ?></td>
							<td>0 / 0</td>
							<td>0</td>
							<td>-</td>
							<td>
								<?php
									$db2 = new PdoDb();
									$req2 = $db->prepare('SELECT `id`, `win` FROM `games` WHERE `user`=:id ORDER BY `win` DESC LIMIT 0, 1;');
									$req2->bindParam(':id', $id, PDO::PARAM_INT);
									$req2->execute();
									$any2 = false;

									while (list($wid, $win) = $req2->fetch(PDO::FETCH_NUM))
									{
										?>
											<a href="/admin/game.php?id=<?php echo $wid; ?>"><?php echo $win; ?></a>
										<?php
										$any2 = true;
										break;
									}

									if (!$any2)
									{
										echo '-';
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

	<script>
		$(document).ready(function() {
			$('select.ban-select').change(function() {
				var id = parseInt($($(this).parent().parent().find('td')[0]).text());
				var value = parseInt($(this).val());

				$.get('/admin/setban.php?id=' + id + '&ban=' + value, function(result) {
					if (result.trim() == 'true') {
						alertify.success('<?php echo getL10n("Message-ChangesSaved"); ?>'); 
					} else {
						alertify.error('<?php echo getL10n("Message-CouldNotSave"); ?>'); 
					}
				});
			});
		});
	</script>

	<nav>
		<ul class="pagination">
			<li>
				<a href="#" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<li class="active">
				<a href="#">1</a>
			</li>

			<li>
				<a href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>

	<script>
		///////////////////////////////////////////////////// user search field /////////////////////////////////////////////////////
		window.disableDefaultSearch = true;
		
		$(document).ready(function() {
			var lastResult = [];

            var func = function (text, f) {
                $.get('/admin/listusers.php', function(result) {
                	result = JSON.parse(result);
                	lastResult = [];
                	var count = 0;

                	text = text.toLowerCase();
                    _.each(result, function (item) {
                    	if (lastResult.length > 10) {
                    		return;
                    	}

                        if (item[1].length >= text.length && text == item[1].substr(0, text.length).toLowerCase()) {
                            lastResult.push(item);
                        } 
                    });

                    //lastResult = lastResult.concat([[0, '...']]);
                	f(_.map(lastResult, function(r) { return r[1]; }));
                });
            };

            $('#search-area').searchPlugin(func, function (keyResult) {
            	location.href = '/admin/user.php?id=' + lastResult[keyResult][0];
            });
		});
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>