<?php
$tablename=$what;
if ($this->data->table_exists($what)) {
        $out.= "<div class='well columns'>
		<form class='' action='?act=show&what=$what' method='post' name='add$what'> 
		<h1>Search $what</h1>
	<p>id:$id $function $act</p>
	<hr>
		<fieldset>";
        
    $max_fields=12;
    $out.="<div class='row-fluid form-wrap'>";


    $sql="SELECT * FROM $tablename";
//echo $sql;
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $i = pg_num_fields($cur);
    if ($i>=$max_fields) {
        $span=4;
        $input_rows=round($i/(12/$span));
    } else {
        $input_rows=1000;
        $span=1;
    }

    $out.="<div class='span$span'>";
    for ($j = 0; $j < $i; $j++) {
        if (($j % $input_rows == 0)&&($j>0)) {
            $out.="</div><div class='span$span'>";
        }
        //echo "column $j\n";
        $fieldname = pg_field_name($cur, $j);
        $fieldtype = pg_field_type($cur, $j);
        $ok="";
        $label=ucfirst($fieldname);
        if ($fieldname != 'id') {
            //$out.= "F:$fieldname:$fieldtype\n";
            if ((($fieldname=='type')||($fieldname=='type_id'))&&($ok=="")) {
                $ok=1;
                $listid=$this->db->GetVal("select distinct list_id from listitems where id>0 and id in (select distinct $fieldname from $tablename)")*1;
                if ($listid>0) {
                    $def=$this->db->GetVal("select id from listitems where list_id=$listid and default_value='1' limit 1");
                    $sql="SELECT id, name FROM listitems where list_id=$listid ORDER by name";
                } else {
                    $list_id=$this->db->GetVal("select distinct list_id from listitems where id>0 and id in (select distinct $fieldname from $tablename)");
                    $sql="SELECT id, name from listitems where list_id=$subtype ORDER by name";
                }
                $input[$j]=$this->html->htlist($fieldname, $sql, $res[type], 'none', '', $def);
                $out.= "<label>$label</label>$input[$j]\n";
            }
            if (($this->utils->contains('_id', $fieldname))&&($ok=="")) {
                $ok=1;
                $tokens=explode("_", $fieldname);
                $listid=$this->db->GetVal("select distinct list_id from listitems where id>0 and id in (select distinct $fieldname from $tablename)")*1;
                if ($listid>0) {
                    $def=$this->db->GetVal("select id from listitems where list_id=$listid and default_value='1' limit 1");
                    $sql="SELECT id, name FROM listitems where list_id=$listid ORDER by name";
                } else {
                    $list_id=$this->db->GetVal("select distinct list_id from listitems where id>0 and id in (select distinct $fieldname from $tablename)");
                    $sql="SELECT id, name from listitems where list_id=$subtype ORDER by name";
                }
                          
                $input[$j]=$this->html->htlist($fieldname, $sql, $res[$fieldname], 'none', '', $def);
                $out.= "<label>".ucfirst($tokens[0])."</label>$input[$j]\n";
            }
            if ((($fieldname=='descr')||($fieldname=='addinfo')||($fieldname=='values')||($fieldname=='data')||($fieldname=='addr')||($fieldname=='address')||($fieldname=='activities'))&&($ok=="")) {
                $ok=1;
                $out.="	<label>$label</label>
			<textarea name='$fieldname' class='' >$res[$fieldname]</textarea>\n\n";
            }
            if ((($fieldtype=='date')||($fieldtype=='datetime')||($fieldtype=='timestamp'))&&($ok=="")) {
                $ok=1;
                $out.="	<label>$label</label>
			<input type='text' data-datepicker='datepicker' name='$fieldname' value='$res[$fieldname]' class='' placeholder='DD.MM.YYYY'/>\n\n";
            }
            if ((($fieldtype=='bool'))&&($ok=="")) {
                $ok=1;
                if ($res[$fieldname]=='t') {
                    $chk_[$fieldname]='checked';
                }
                $out.="<div class='checkbox'><input type='checkbox' name='$fieldname' value='1' $chk_[$fieldname] /><label class=''>$label</label></div>\n\n";
                //$outchk.="\tif($res[$fieldname]=='t')$chk_[$fieldname]='checked';\n";
            }
            if ($ok=="") {
                $ok=1;
                $out.="	<label>$label</label>
			<input type='text' name='$fieldname' value='$res[$fieldname]' class='' placeholder=''/>\n\n";
            }
        }
    }



    $out.="
	</div></div>
	</fieldset>	
	<fieldset>
		<p> </p>
	".$this->html->form_confirmations()."
		<button type='submit' class='btn btn-primary' name='act' value='save'>Submit</button> 
	<div class='spacer'></div>
	</fieldset>
	</form>
	</div>";
} else {
    $out.=$this->html->error($this->html->message("Object '$what' not found.", "404", 'alert-error'));
}



$body.= $out;
