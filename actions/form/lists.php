<?php
if ($what == 'lists') {
    if ($act=='edit') {
        $sql="select * from $what WHERE id=$id";
        $res=$this->utils->escape($this->db->GetRow($sql));
    } else {
        $res[id]=$this->db->GetVal("select max(id) from $what")+1;
    }
        $out.= "<div class='well columns form-wrap'>
			<form class='' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='add$what'> 
			<input type='hidden' name='reflink' value='$reflink'>
			<h1>Edit $what</h1>
			<p>id:$id</p>
			<hr>
			<fieldset>
			<label>ID</label>
			<input type='text' name='id' value='$res[id]' class='span12' placeholder=''/>
			
			<label>Name</label>
			<input type='text' name='name' value='$res[name]' class='span12' placeholder=''/>

			<label>Alias</label>
			<input type='text' name='alias' value='$res[alias]' class='span12' placeholder=''/>

			<label>Descr</label>
			<textarea name='descr' class='span12' >$res[descr]</textarea>

			<label>Addinfo</label>
			<textarea name='addinfo' class='span12' >$res[addinfo]</textarea>

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
