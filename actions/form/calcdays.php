<?php

		

		$out.= "
			
			<div id='stylized' class='well'>
			  <form id='form1' name='form1' method='post' action=''>
			    <h1>$what </h1>
			    <p>$what <br>$referring</p>   
			    <dt><label>Date 1</label><input name='date1' id='date1' value='' data-datepicker='datepicker' class='date' type='text' placeholder='DD.MM.YYYY'/></dt>
				<dt><label>Date 2</label><input name='date2' id='date2' value='' data-datepicker='datepicker' class='date' type='text' placeholder='DD.MM.YYYY'/></dt>
				<dt><label>Result</label><span name='result' id='result' value='' class='date'></span>    
			  
			<div id='eventform_'></div>
			 
				<div class='spacer'></div>
				<button type='button' name='act' value='Calc' id='button' onClick='
				var date1=document.getElementById(\"date1\").value;
				var date2=document.getElementById(\"date2\").value;
				ajaxFunction(\"result\",\"?csrf=$GLOBALS[csrf]&act=append&what=calcdays&debug=1&date1=\"+date1+\"&date2=\"+date2);
				
				document.getElementById(\"result2\").innerHTML=\"Wait...\";
				' 
				
				language='javascript'>Calculate</button><br>
			    <div class='spacer'></div>
			  </form>
			</div>
			";
		

	
	

$body.=$out;
