<?php
$tablename=$what;
if ($this->data->table_exists($what)) {
    if ($act=='edit') {
        if ($this->data->field_exists($what, 'id')) {
            $sql="select * from $what WHERE id=$id";
            $res=$this->utils->escape($this->db->GetRow($sql));
        }
    } else {
        if ($this->data->field_exists($what, 'id')) {
            $sql="select * from $what WHERE id=$refid";
            $res2=$this->db->GetRow($sql);
        }
        $res[active]='t';
    }
    
    $out.=$this->html->form_start($what, 0, '');
    $out.=$this->html->form_hidden('reflink', $reflink);
    $out.=$this->html->form_hidden('id', $id);
    $out.=$this->html->form_hidden('reference', $reference);
    $out.=$this->html->form_hidden('refid', $refid);
    


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
                }

                $input[$j]=$this->html->htlist($fieldname, $sql, $res[$fieldname], 'none', '', $def);
                $out.= "<label>".ucfirst($tokens[0])."</label>$input[$j]\n";
            }
            if ((($fieldname=='descr')||($fieldname=='addinfo')||($fieldname=='values')||($fieldname=='data')||($fieldname=='addr')||($fieldname=='address')||($fieldname=='activities'))&&($ok=="")) {
                $ok=1;
                $out.=$this->html->form_textarea($fieldname, $res[$fieldname], $label);
            }
            if ((($fieldtype=='date')||($fieldtype=='datetime')||($fieldtype=='timestamp'))&&($ok=="")) {
                $ok=1;
                $out.=$this->html->form_date($fieldname, $res[$fieldname], $label);
            }
            if ((($fieldtype=='bool'))&&($ok=="")) {
                $ok=1;
                $out.=$this->html->form_chekbox($fieldname, $res[$fieldname], $label);
            }
            if ($ok=="") {
                $ok=1;
                $out.=$this->html->form_text($fieldname, $res[$fieldname], $label);
            }
        }
    }



    $out.="
		</div>
	</div>";
    $out.=$this->html->form_textarea($fieldname, $res[$fieldname], $label);
    $out.=$this->html->form_submit('Save');
    $out.=$this->html->form_end();
    if (($GLOBALS[access][main_admin])&&($GLOBALS[access][view_debug])) {
        $out.=$this->html->tag("<a href='?act=tools&what=genform&tablename=$what'>Generate code</a>", "span", "btn");
    }
} else {
    $out.=$this->html->error($this->html->message("Object '$what' not found.", "404", 'alert-error'));
}



$body.= $out;
