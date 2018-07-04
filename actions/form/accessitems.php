<?php
if ($what == 'accessitems'){

		if ($act=='edit'){
			HT_error("Editing not applied");
			exit;
		}
		$opt['url']="?act=tools&what=addaccessitems";
		$opt['class']="form-horizontal";
		$out.=$this->html->form_start($what,0,'',$opt);
		$out.=$this->html->form_hidden('reflink',$reflink);
		$out.=$this->html->form_hidden('id',$id);
		$out.=$this->html->form_hidden('reference',$reference);
		$out.=$this->html->form_hidden('refid',$refid);
		$out.=$this->html->form_text('name','','Name','Name',2);
		$out.=$this->html->form_submit('Save');
		$out.=$this->html->form_end();
		
		
		$out2.= "<div class='well columns form-wrap'>
			<form class='' action='?act=tools&what=addaccessitems' method='post' name='add$what'> 
			<input type='hidden' name='reflink' value='$reflink'>
			<h1>Add $what</h1>
			<p></p>
			<hr>
			<fieldset>

			
			<label>Name</label>
			<input type='text' name='table' value='$res[name]' class='span12' placeholder=''/>

			</fieldset>	
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
