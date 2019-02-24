<?php
$out.= "
	
	<div id='stylized' class='well'>
	  <form id='form1' name='form1' method='post' action=''>
	    <h1>$what </h1>
	    <p>$what <br>$referring</p>   
	    <dt><label>Subset 1</label><textarea name='data1' id='data1' class='span12'></textarea></dt>
		<dt><label>Subset 2</label><textarea name='data2' id='data2' class='span12'></textarea></dt>
		<span name='result' id='result' value='' class=''></span><span id='result2'></span>    
	  
	<div id='eventform_'></div>
	 
		<div class='spacer'></div>
		<button type='button' name='act' value='Calc' id='button' onClick='
		var data1=document.getElementById(\"data1\").value;
		var data2=document.getElementById(\"data2\").value;
		ajaxFunction(\"result\",\"?csrf=$GLOBALS[csrf]&act=append&what=subset&debug=1&data1=\"+data1+\"&data2=\"+data2);
		
		document.getElementById(\"result2\").innerHTML=\"Wait...\";
		' 
		
		language='javascript'>Calculate</button><br>
	    <div class='spacer'></div>
	  </form>
	</div>
	";
$body.=$out;