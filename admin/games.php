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
		<?php
			$start = 0;
			$limit = 15;
			$page = 1;
			$maxPagesCount = 20;

			$user = false;
			if (isset($_GET['id']))
			{
				$user = intval($_GET['id']);

				if ($user == 0)
				{
					$user = false;
				}
			}
		?>

		<div class="panel-heading">Тиражи</div>

		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th>Пользователь</th>
					<th>Шары</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$db = new PdoDb();

					if ($user == false)
					{
						$req = $db->prepare('SELECT COUNT(*) FROM `games` WHERE LENGTH(`balls`) > 0;');
					}
					else
					{
						$req = $db->prepare('SELECT COUNT(*) FROM `games` WHERE LENGTH(`balls`) > 0 AND `user`=:user;');
						$req->bindParam(':user', $user, PDO::PARAM_INT);
					}

					$req->execute();
					$count = $req->fetchColumn();

					// limit pages
					if ($count > $limit * $maxPagesCount)
					{
						$count = $limit * $maxPagesCount;
					}

					if (isset($_GET['page']))
					{
						$page = intval($_GET['page']);
						$start = ($page - 1) * $limit;

						if ($start < 0)
						{
							$start = 0;
						}

						if ($start >= $count)
						{
							$start = floor($count / $limit) * $limit;
						}
					}

					$db = new PdoDb();

					if ($user == false)
					{
						$req = $db->prepare('SELECT t1.id, t1.user, t1.balls, t2.login FROM `games` AS t1, `users` AS t2 WHERE t1.user = t2.id AND LENGTH(t1.balls) > 0 ORDER BY t1.id DESC LIMIT :start, :limit;');
						$req->bindParam(':start', $start, PDO::PARAM_INT);
						$req->bindParam(':limit', $limit, PDO::PARAM_INT);
					}
					else
					{
						$req = $db->prepare('SELECT t1.id, t1.user, t1.balls, t2.login FROM `games` AS t1, `users` AS t2 WHERE t1.user = t2.id AND LENGTH(t1.balls) > 0 AND t1.user = :user ORDER BY t1.id DESC LIMIT :start, :limit;');
						$req->bindParam(':user', $user, PDO::PARAM_INT);
						$req->bindParam(':start', $start, PDO::PARAM_INT);
						$req->bindParam(':limit', $limit, PDO::PARAM_INT);
					}

					$req->execute();

					while (list($id, $userId, $balls, $login) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							<tr>
								<td><a href="/admin/game.php?id=<?php echo $id; ?>"><?php echo $id; ?></a></td>
								<td><a href="/admin/user.php?id=<?php echo $userId; ?>"><?php echo $login; ?></a></td>
								<td><?php echo $balls; ?></td>
							</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</div>

	<nav>
		<ul class="pagination">
			<li>
				<a href="/admin/games.php?page=1&id=<?php echo $user ? $user : 0; ?>" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<?php
				$pages = ceil($count / $limit);

				for ($i = 0; $i < $pages; ++$i)
				{
					?>
						<li <?php echo $i == $page - 1 ? 'class="active"' : ''; ?>>
							<a href="/admin/games.php?page=<?php echo ($i + 1); ?>&id=<?php echo $user ? $user : 0; ?>"><?php echo ($i + 1); ?></a>
						</li>
					<?php
				}
			?>

			<li>
				<a href="/admin/games.php?page=<?php echo $pages; ?>&id=<?php echo $user ? $user : 0; ?>" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>

	<script>
		///////////////////////////////////////////////////// draws search field ////////////////////////////////////////////////////
		window.disableDefaultSearch = true;
		
		$(document).ready(function() {
			var lastResult = [];

            var func = function (text, f) {
                $.get('/admin/listdraws.php', function(result) {
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
            	location.href = '/admin/game.php?id=' + lastResult[keyResult][0];
            });
		});
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	</script>

	<?php require_once('footer.php'); ?>
<?php
	}
?>