<?php
if ($what == 'fastmenu') {
    if ($act=='edit') {
        $sql="select * from $what WHERE id=$id";
        $res=$this->utils->escape($this->db->GetRow($sql));
    } else {
        $sql="select * from $what WHERE id=$refid";
        $res2=$this->db->GetRow($sql);
        $res[active]='t';
    }

        $out.= "<div class='well columns form-wrap'>
			<form class='' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='add$what'> 
				<input type='hidden' name='reflink' value='$reflink'>
				<input type='hidden' name='id' value='$id'>
				<input type='hidden' name='reference' value='$reference'>
				<input type='hidden' name='refid' value='$refid'>
				<h1>Edit $what</h1>
				<p>id:$id</p>
				<hr>
				<fieldset>
		<label>Name</label>
		<input type='text' name='name' value='$res[name]' class='span5' placeholder=''/>

		<label>Date</label>
		<input type='text' data-datepicker='datepicker' name='date' value='$res[date]' class='span5' placeholder='DD.MM.YYYY'/>

		<label>Gid</label>
		<input type='text' name='gid' value='$res[gid]' class='span5' placeholder=''/>

		<label>Menu</label>

		<textarea name='menu' class='span12' rows='40' >$res[menu]</textarea>


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
