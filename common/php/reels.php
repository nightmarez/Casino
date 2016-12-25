<?php
	function genReels($count)
	{
		for ($i = 0; $i < $count; ++$i)
		{
			?>
				<div class="reel" id="reel-<?php echo $i; ?>">
				</div>
			<?php
		}
	}
?>