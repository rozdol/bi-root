<?php
if ($what == 'import_lists'){
		$name=$this->data->get_name('lists',$refid);
		$out.= "<div class='well columns form-wrap'>
			<form class='' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='add$what'> 
				<input type='hidden' name='reflink' value='$reflink'>
				<input type='hidden' name='id' value='$id'>
				<input type='hidden' name='reference' value='$reference'>
				<input type='hidden' name='refid' value='$refid'>
				<h1>Import Items for $name</h1>
				<hr>
				<fieldset>
				<label>Data <p>Format:<br>Name;Alias;qty;values;text1;text2;num1;num2;descr;add.info</p></label>
				<textarea name='data' class='span12' rows=20>$res[data]</textarea>
		<fieldset>
			<p> </p>
			".$this->html->form_confirmations()."
		<button type='submit' class='btn btn-primary' name='act' value='save'>Submit</button> 
			<div class='spacer'></div>
		</fieldset>
		</form>
		</div>";
		
	}

	
$body.=$out;
