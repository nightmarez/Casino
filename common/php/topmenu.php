<div id="top-menu-center">
	<div>
		<a id="top-menu-exit-button" href="#">Exit</a>
		<a id="top-menu-help-button" href="#">Help</a>
	</div>
	<div>
		<a id="top-menu-topup-button" href="#">Top up</a>
		<a id="top-menu-payin-button" href="#">Pay in</a>
	</div>
	<ul>
		<li><a id="top-menu-fullscreenbutton" href="#"></a></li>
		<li><a id="top-menu-sound-button" href="#"></a></li>
		<li><a id="top-menu-music-button" href="#"></a></li>		
	</ul>
</div>

<?php
	require_once('../utils.php');

	if (isSpinNumberVisible())
	{
		?>
			<div id="top-menu-spin-id">#000000</div>
		<?php
	}
?>