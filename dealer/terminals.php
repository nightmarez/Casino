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
		<div class="panel-heading">Терминалы</div>

		<table class="table">
			<thead>
				<tr>
					<th>№</th>
					
				</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
			
	<?php require_once('footer.php'); ?>
<?php
	}
?>