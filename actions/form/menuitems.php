<?php
if ($what == 'menuitems') {
    if ($act=='edit') {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
    } else {
        $sql="select * from $what WHERE id=$refid";
        $res2=$this->db->GetRow($sql);
        $res[active]='t';
        $res[hidden]='f';
    }
    if (($res[hidden]=='t')||($res[hidden]=='')) {
        $hiddenchecked='checked';
    } else {
        $hiddenchecked='';
    }
        $out.= "<div class='well columns form-wrap'>
			<form class='' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='add$what'> 
				<input type='hidden' name='reflink' value='$reflink'>
				<input type='hidden' name='id' value='$id'>
				<h1>Edit $what</h1>
				<p>id:$id</p>
				<hr>
	<fieldset>
		<label>Name</label>
		<input type='text' name='name' value='$res[name]' class='span12' placeholder=''/>

		<label>Link</label>
		<input type='text' name='link' value='$res[link]' class='span12' placeholder=''/><a href='$res[link]'>$res[link]</a>
		<label><input type='checkbox' name='hidden' value='1' $hiddenchecked /> Hidden</label>
		<label>Descr</label>
		<textarea name='descr' class='span12' >$res[descr]</textarea>

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
