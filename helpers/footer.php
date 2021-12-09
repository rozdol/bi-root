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
	<style> @media print { a[href]::after { content: none !important; } } </style>
</body>
</html>

EOF;
