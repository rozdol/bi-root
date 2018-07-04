<?php
$tablename=$what;
if ($what == $tablename) {
    $sql="SELECT * FROM $tablename";

    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }

    $vals=array();
    $i = pg_num_fields($cur);
    for ($j = 0; $j < $i; $j++) {
        $fieldname = pg_field_name($cur, $j);
        $fieldtype = pg_field_type($cur, $j);
        $ok="";
        if ((($fieldtype=='date')||($fieldtype=='timestamp'))&&($ok=="")) {
            $ok=1;
            $vals[$fieldname]=$this->dates->F_date($this->html->readRQ($fieldname), 1);
        } else {
            if ((($fieldtype=='int4')||($fieldtype=='int2')||($fieldtype=='bool')||($fieldtype=='int8')||($fieldtype=='float8')||($fieldtype=='integer')||($fieldtype=='float'))&&($ok=="")) {
                $val=$this->html->readRQn($fieldname);
                if (($fieldname=='id')&&($val==0)) {
                } else {
                    $vals[$fieldname]=$val;
                }
            } else {
                $vals[$fieldname]=$this->html->readRQ($fieldname);
            }
        }
    }
    if ($id==0) {
        $id=$this->db->insert_db($tablename, $vals);
    } else {
        $id=$this->db->update_db($tablename, $id, $vals);
    }
//$err="Some error";
    if ($err=="") {
        $out.= $this->html->refreshpage($GLOBALS[reflink], 1, "<div class='alert alert-info'>Data for $tablename has been saved.<br>ID:$id</div>");
    } else {
        $out.= $this->html->message("Data for $tablename has not been saved.<br>ID:$id".$this->html->pre_display($vals), "$function error", "alert-error");
    }
}

$body.=$out;
