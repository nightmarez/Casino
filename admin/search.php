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
		<div class="panel-heading">Результаты поиска</div>

		<table class="table">
			<?php
				$search = '';
				if (isset($_GET['q']))
				{
					$search = htmlspecialchars($_GET['q']);
				}
				$search = '%' . $search . '%';

				$db = new PdoDb();
				$req = $db->prepare('SELECT `id`, `login` FROM `users` WHERE `login` LIKE :search;');
				$req->bindParam(':search', $search, PDO::PARAM_STR);
				$req->execute();
				$any = false;

				while (list($id, $login) = $req->fetch(PDO::FETCH_NUM))
				{
					if ($level == 1) {
						continue;
					}

					$any = true;
					?>
						<tr>
							<td><a href="/admin/user.php?id=<?php echo $id; ?>"><?php echo htmlentities($login); ?></a></td>
						</tr>
					<?php
				}

				if (!$any)
				{
					?>
						<tr>
							<td>
								Ничего не найдено.
							</td>
						</tr>
					<?php
				}
			?>
		</table>
	</div>

	<?php require_once('footer.php'); ?>
<?php
	}
?>