<?php
if ($what == 'ip2country'){
	$out.= "<dic class='well span5'> 
	<input type='hidden' name='nopager' value='1'>
		<fieldset><label>IP</label><input name='ip' id='ip' value='217.27.32.196' class='date' type='text' placeholder=''/>
		<label></label><span id='result' class='btn'> </span>
		</fieldset>	
		<div class='spacer'> </div><br>
		<button class='btn btn-primary'  type='button' name='act' value='Calc' id='button' onClick=
		'
		var ip=document.getElementById(\"ip\").value;
		ajaxFunction(\"result\",\"?csrf=$GLOBALS[csrf]&act=append&what=ip2country&debug=1&ip=\"+ip);
		' 
		language='javascript'>Get Counry</button>
		<div class='spacer'></div>
		</fieldset>
		</div>";
	
}

?>
$body.=$out;
