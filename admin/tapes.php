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
		if (!isset($_GET['gameid']))
		{
			die();
		}

		$gameid = intval($_GET['gameid']);

		if ($gameid <= 0)
		{
			die();
		}

		$presets = array();
		if (isset($_GET['presetid'])) 
		{
			$presets[] = htmlspecialchars($_GET['presetid']);
		}
		else
		{
			$db = new PdoDb();
			$req = $db->prepare('SELECT `presetid` FROM `presets` WHERE `gameid`=:gameid;');
			$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
			$req->execute();

			while (list($preset) = $req->fetch(PDO::FETCH_NUM))
			{
				if (!in_array($preset, $presets)) {
					$presets[] = $preset;
				}
			}
		}

		$gamename = '';
		$gamereels = 0;
		$db = new PdoDb();
		$req = $db->prepare('SELECT `title`, `reels` FROM `programs` WHERE `id`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();

		while (list($title, $reels) = $req->fetch(PDO::FETCH_NUM))
		{
			$gamename = htmlspecialchars($title);
			$gamereels = intval($reels);
			break;
		}
		
		$symbols = array();
		$symbolspath = '../games/' . getGamePathById($gameid) . '/imgs/symbols/';
		$req = $db->prepare('SELECT `id`, `name`, `filename`, `attr` FROM `symbols` WHERE `gameid`=:gameid;');
		$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
		$req->execute();

		while (list($itemid, $itemname, $itemfilename, $itemattr) = $req->fetch(PDO::FETCH_NUM))
		{
			$fullitemname = false;

			if (file_exists($symbolspath . '/' . $itemfilename . '.jpg')) {
				$fullitemname = $symbolspath . '/' . $itemfilename . '.jpg';
			} else if (file_exists($symbolspath . '/' . $itemfilename . '.png')) {
				$fullitemname = $symbolspath . '/' . $itemfilename . '.png';
			}

			$symbols["'" . $itemname . "'"] = $fullitemname;
		}
	?>

	<div class="panel panel-default">
		<div class="panel-heading">Игра <a href="/admin/program.php?id=<?php echo $gameid; ?>"><b><?php echo $gamename ?></b></a>, <?php if (count($presets) > 1 || !isset($_GET['presetid'])) { echo "все ленты"; } else { echo "лента <b>$presets[0]</b>"; } ?></div>

		<div>
			<table class="table">
				<thead>
					<tr>
						<th>Номер</th>
						<th>Ленты (<?php echo $gamereels; ?>)</th>
						<th>Действия</th>
						<th>Статистика</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$presetlines = array();

						foreach ($presets as $preset)
						{
							$db = new PdoDb();
							$req = $db->prepare('SELECT `id`, `preset` FROM `presets` WHERE `presetid`=:presetid AND `gameid`=:gameid ORDER BY `stringid`;');
							$req->bindParam(':presetid', $preset, PDO::PARAM_STR);
							$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
							$req->execute();

							while (list($id, $presetvalue) = $req->fetch(PDO::FETCH_NUM))
							{
								if (!array_key_exists($preset, $presetlines)) {
									$presetlines[$preset] = array();
								}

								$presetlines[$preset][] = $presetvalue;
							}
						}

						foreach ($presetlines as $presetid => $preset) {
							$tmp = array();

							for ($i = 0; $i < count($preset); ++$i) {
								$preset[$i] = explode(',', $preset[$i]);
							}

							for ($i = 0; $i < count($preset); ++$i) {
								for ($j = 0; $j < count($preset[$i]); ++$j) {
									$tmp[$j][$i] = $preset[$i][$j];
								}
							}

							$preset = $tmp;

							?>
								<tr>
									<td>
										<b>
											<a href="/admin/tapes.php?gameid=<?php echo $gameid; ?>&presetid=<?php echo $presetid; ?>"><?php echo $presetid; ?></a>
										</b>
									</td>
									<td>
										<table class="table" style="width: 600px;">
											<thead>
												<tr>
													<?php
														for ($i = 0; $i < $gamereels; ++$i)
														{
															?>
																<th style="text-align: center;"><?php echo ($i + 1); ?></th>
															<?php
														}
													?>
												</tr>
											</thead>
											<tbody>
												<?php
													for ($i = 0; $i < count($preset); ++$i)
													{
														?><tr><?php
															for ($j = 0; $j < $gamereels; ++$j)
															{
																if (isset($preset[$i])) {
																	if (isset($preset[$i][$j])) {
																		$item = trim($preset[$i][$j]);

																		if (isset($symbols[$item]))
																		{
																			?>
																				<td class="vertical-reel">
																					<div style="float: left; width: 64px; margin-right: 10px;">
																						<img src="<?php echo $symbols[$item]; ?>" style="float: left; width: 64px; height: 64px;">
																						<div style="text-align: center; float: left; width: 64px;"><?php echo str_replace("'", '', $item); ?></div>
																					</div>
																				</td>
																			<?php
																		}
																	} else {
																		?>
																			<td class="vertical-reel">
																				<div style="float: left; width: 64px; margin-right: 10px;">
																					<img src="" style="float: left; width: 64px; height: 64px;">
																					<div style="text-align: center; float: left; width: 64px;">&nbsp;</div>
																				</div>
																			</td>
																		<?php
																	}
																} else {
																	?>
																		<td class="vertical-reel">
																			<div style="float: left; width: 64px; margin-right: 10px;">
																				<img src="" style="float: left; width: 64px; height: 64px;">
																				<div style="text-align: center; float: left; width: 64px;">&nbsp;</div>
																			</div>
																		</td>
																	<?php
																}
															}
														?></tr><?php
													}
												?>

												<tr>
													<?php
														for ($i = 0; $i < $gamereels; ++$i)
														{
															?>
																<td>
																	<form mathod="GET" action="addreelsymbol.php">
																		<input type="hidden" name="gameid" value="<?php echo $gameid; ?>">
																		<input type="hidden" name="presetid" value="<?php echo $presetid; ?>">
																		<input type="hidden" name="stringid" value="<?php echo $i; ?>">
																		<select name="name">
																			<?php
																				$req = $db->prepare('SELECT `name` FROM `symbols` WHERE `gameid`=:gameid ORDER BY `name`;');
																				$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
																				$req->execute();

																				while (list($itemname) = $req->fetch(PDO::FETCH_NUM))
																				{
																					?><option value="<?php echo $itemname; ?>"><?php echo $itemname; ?></option><?php
																				}
																			?>
																		</select>
																		<input type="submit" value="Добавить">
																	</form>
																</td>
															<?php
														}
													?>
												</tr>
											</tbody>
										</table>
									</td>
									<td>
										<form method="GET" action="addreel.php">
											<input type="hidden" name="gameid" value="<?php echo $gameid; ?>">
											<input type="hidden" name="presetid" value="<?php echo $presetid; ?>">
											<input type="submit" value="Добавить ленту">
										</form>

										<br>

										<form id="removereel" method="GET" action="removereel.php">
											<input type="hidden" name="gameid" value="<?php echo $gameid; ?>">
											<input type="hidden" name="presetid" value="<?php echo $presetid; ?>">
											<select name="index">
												<?php
													for ($k = 0; $k < $gamereels; ++$k) {
														?>
															<option value="<?php echo $k; ?>"><?php echo ($k + 1); ?></option>
														<?php
													}
												?>
											</select>
											<input type="submit" value="Удалить ленту">
										</form>

										<script>
											$(document).ready(function() {
												_.each($('#removereel select'), function(select) {
													select = $(select);
													select.change(function() {
														var optid = parseInt(select.val());
														$('.vertical-reel').css('background-color', 'white');
														$('.vertical-reel:nth-child(' + (optid + 1) + ')').css('background-color', 'silver');
														$('.vertical-reel:nth-child(' + (optid + 1) + ')').animate({backgroundColor: "white"}, 800);
													});
												});
											});
										</script>
									</td>
									<td>
										<table class="table">
											<tbody>
												<tr>
													<td>Всего игр</td>
													<td>
														<?php
															$req = $db->prepare('SELECT COUNT(*) FROM `pgames` WHERE `gameid`=:gameid AND `presetid`=:presetid;');
															$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
															$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
															$req->execute();
															echo $req->fetchColumn(); 
														?>
													</td>
												</tr>
												<tr>
													<td>Потрачено</td>
													<td>
														<?php
															$req = $db->prepare('SELECT SUM(`bet` * `betlines`) AS total FROM `pgames` WHERE `gameid`=:gameid AND `presetid`=:presetid;');
															$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
															$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
															$req->execute();
															$totalSpent = $req->fetch(PDO::FETCH_ASSOC)['total'];
															echo $totalSpent;
														?>
													</td>
												</tr>
												<tr>
													<td>Выиграно</td>
													<td>
														<?php
															$req = $db->prepare('SELECT SUM(`win`) AS total FROM `pgames` WHERE `gameid`=:gameid AND `presetid`=:presetid;');
															$req->bindParam(':presetid', $presetid, PDO::PARAM_STR);
															$req->bindParam(':gameid', $gameid, PDO::PARAM_INT);
															$req->execute();
															$totalWin = $req->fetch(PDO::FETCH_ASSOC)['total'];
															echo $totalWin;
														?>
													</td>
												</tr>
												<tr>
													<td>Коэффициент выигрыша</td>
													<td>
														<?php
															if ($totalSpent != 0) {
																echo (round($totalWin / $totalSpent * 100, 2)) . '%';
															}
														?>
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<a href="/admin/bot.php?gameid=<?php echo $gameid; ?>&presetid=<?php echo $presetid; ?>">Провести эмуляцию игр</a>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<?php require_once('footer.php'); ?>
<?php
	}
?>