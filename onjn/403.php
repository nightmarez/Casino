<?php
	require_once('utils.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Access Denied</title>
		<?php require_once('head.php'); ?>
	</head>
	<body>
		<div id="modal-window-outer">
			<div id="modal-window">
				
				<h1 style="text-align: center; padding-top: 50px;">Access Denied</h1>

				<form action="index.php" method="GET">
					<div style="position: absolute; left: 50%; width: 100px;">
						<div style="position: absolute; left: -50%; width: 100px;">
							<button style="margin-bottom: -50px; width: 100px;" type="submit" value="Back" id="button-login">Back</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</body>
</html>