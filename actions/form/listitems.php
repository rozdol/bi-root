<?php
if ($what == 'listitems') {
    if ($act=='edit') {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
        if ($res['default_value']=='t') {
            $chk_default_value='checked';
        }
    } else {
        $sql="select * from $what WHERE id=$refid";
        $res2=$this->db->GetRow($sql);
        $res['list_id']=$refid;
    }
        $out.= "<div class='well columns form-wrap'>
			<form class='' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='add$what'> 
				<input type='hidden' name='reflink' value='$reflink'>
				<input type='hidden' name='id_old' value='$id'>
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

	  ";
      $sql="SELECT id, name FROM lists WHERE id>0 ORDER by name";
      $input3=$this->html->htlist('list_id', $sql, $res['list_id'], '');
      $out.= "<label>List</label>$input3
		<label>Qty</label>
		<input type='text' name='qty' value='$res[qty]' class='span12' placeholder=''/>

		<label><input type='checkbox' name='default value' value='1' $chk_default_value /> Default_value</label>

		<label>Values</label>
		<textarea name='values' class='span12' >$res[values]</textarea>
		
		<label>Text1</label>
			<input type='text' name='text1' value='$res[text1]' class='span12' placeholder=''/>

			<label>Text2</label>
			<input type='text' name='text2' value='$res[text2]' class='span12' placeholder=''/>

			<label>Num1</label>
			<input type='text' name='num1' value='$res[num1]' class='span12' placeholder=''/>

			<label>Num2</label>
			<input type='text' name='num2' value='$res[num2]' class='span12' placeholder=''/>
		

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
