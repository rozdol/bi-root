<?php
$html = <<< EOF
	<div media="print"  class="noPrint">
		{$content['info']['debug']}
			<footer>
			<hr>
				<div class='footer'>{$content['footer']}</div>
			</footer>
	</div>
	{$content['scripts']}
</body>
</html>

EOF;
