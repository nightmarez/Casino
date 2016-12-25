<?php
	require_once('../utils.php');

	if (!dealerZoneAccess())
	{
		header('Location: /authorized.php');
		die();
	}
	else
	{
?>
	<?php require_once('header.php'); ?>

	<div class="panel panel-default">
		<div class="panel-heading">Категории игр</div>

		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th>Категория</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$db = new PdoDb();
					$req = $db->prepare('SELECT * FROM `categories`;');
					$req->execute();
						
					while (list($id, $title) = $req->fetch(PDO::FETCH_NUM))
					{
						?>
							<tr>
								<td><a href="/dealer/games.php?category=<?php echo $id; ?>"><?php echo $id; ?></a></td>
								<td><a href="/dealer/games.php?category=<?php echo $id; ?>"><?php echo $title; ?></a></td>
							</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</div>

	<?php require_once('footer.php'); ?>
<?php
	}
?>