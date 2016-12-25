<?php
	require_once('utils.php');

	if (isUserUnregistered()) {
		header('Location: login.php');
	}

	if (isset($_POST['roomid'])) {
		$roomid = intval($_POST['roomid']);
	} else {
		if (isset($_GET['roomid'])) {
			$roomid = intval($_GET['roomid']);
		} else {
			$roomid = 0;
		}
	}

	if (isset($_POST['placeid'])) {
		$placeid = $_POST['placeid'];
	} else {
		$placeid = 0;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Users</title>
		<?php require_once('head.php'); ?>

		<style>
			a.product, a.product:link, a.product:hover, a.product:visited {
				border: none;
				text-decoration: none;
				display: block;
				float: left;
			}

			a.product div.image {
				width: 300px;
				height: 300px;
				background-size: contain;
				background-position: center;
				background-repeat: no-repeat;
			}

			a.product div.image:hover {
				-webkit-filter: blur(1px) grayscale(0.2) contrast(150%);
  				filter: blur(1px) grayscale(0.2) contrast(150%);
			}

			a.product span {
				display: block;
				font-size: 18px;
				text-align: center;
				color: black;
			}

			a.product:hover span {
				-webkit-filter: blur(1px) grayscale(0.2) contrast(150%);
  				filter: blur(1px) grayscale(0.2) contrast(150%);
			}
		</style>
	</head>
	<body>
		<?php require_once('header.php'); ?>

		<?php
			$db = new PdoDb();
			$db->beginTransaction();
		?>

		<div class="admin-content panel panel-default">
			<div class="panel-heading">Rooms and places</div>

			<form action="index.php" method="POST">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<label>Room</label>
								<select name="roomid" style="min-width: 150px; min-height: 30px; margin-left: 5px;">
									<option value="0"></option>
									<?php
										$req = $db->prepare('SELECT `id`, `title` FROM `rooms`;');
										$req->execute();

										while (list($croomid, $title) = $req->fetch(PDO::FETCH_NUM)) {
											?>
												<option value="<?php echo $croomid; ?>" <?php if ($croomid == $roomid) { echo 'selected'; } ?>><?php echo $title; ?></option>
											<?php
										}
									?>
								</select>
							</td>

							<?php if ($roomid != 0) { ?>
							<td>
								<label>Places with clients</label>
								<select name="placeid" style="min-width: 150px; min-height: 30px; margin-left: 5px;">
									<option value="0"></option>
									<?php
										$req = $db->prepare('SELECT `id`, `title` FROM `places` WHERE `room`=:room AND `free`=0 ORDER BY `title`;');
										$req->bindParam(':room', $roomid, PDO::PARAM_INT);
										$req->execute();

										while (list($cplaceid, $title) = $req->fetch(PDO::FETCH_NUM)) {
											?>
												<option value="<?php echo $cplaceid; ?>" <?php if ($cplaceid == $placeid) { echo 'selected'; } ?>><?php echo $title; ?></option>
											<?php
										}
									?>
								</select>
							</td>
							<?php } ?>

							<?php if ($placeid != 0 ) { ?>
							<td>
								<input type="button" id="gobutton" value="Go to this room">
							</td>
							<?php } ?>

							<!--
							<td>
								<input type="submit" value="Open">
							</td>
							-->
						</tr>
					</tbody>
				</table>
			</form>
		</div>

		<?php if ($roomid != 0 && $placeid != 0) { ?>
		<div class="admin-content panel panel-default">
			<div class="panel-heading">Menu</div>

			<?php
				$req = $db->prepare('SELECT `id`, `title`, `cost`, `count` FROM `products` WHERE `count` > 0;');
				$req->execute();

				while (list($pid, $ptitle, $pcost, $count) = $req->fetch(PDO::FETCH_NUM)) {
					?>
						<a class="product" href="#">
							<div class="image" style="background-image: url('products/<?php echo $pid; ?>.jpg');"></div>
							<br>
							<span><?php echo htmlspecialchars($ptitle); ?> (cost: <?php echo $pcost; ?>)</span>
							<br>
							<span>In Storage: <i><?php echo $count; ?></i></span>
							<input type="hidden" value="<?php echo $pid; ?>">
						</a>
					<?php
				}
			?>
		</div>
		<?php } ?>

		<?php $db->commit(); ?>

		<script>
			$(document).ready(function() {
				var roomid = parseInt($('select[name=roomid]').val());
				var placeid = parseInt($('select[name=placeid]').val());

				if (roomid > 0 && placeid > 0) {
					(function(roomid, placeid) {
						$('a.product').click(function(e) {
							e.stopPropagation();
							_this = $(this);
							var productid = parseInt(_this.find('input[type=hidden]').val());

							$.get('dosendproduct.php?roomid=' + roomid + '&placeid=' + placeid + '&productid=' + productid, function(response) {
								var count = parseInt(response);

								if (count > 0) {
									_this.find('i').text(count);
									alertify.success('Done!');
								} else if (count == 0) {
									_this.remove();
									alertify.success('Done!');
								} else {
									alertify.error('Some error happens');
								}
							});

							return false;
						});
					})(roomid, placeid);

					$('#gobutton').click(function() {
						location.href = 'room.php?id=' + <?php echo $roomid; ?>;
					});
				}

				$('select').change(function() {
					$('form').submit();
				});
			});
		</script>

		<?php require_once('footer.php'); ?>
	</body>
</html>