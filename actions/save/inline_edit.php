<?php
    //$out.= "<pre>";print_r($_POST);$out.= "</pre>";//exit;
    $id=$this->html->readRQn('id');
    $table=$this->html->readRQ('table');
    $field=$this->html->readRQ('field');
    $value=$this->html->readRQ('value');
    $is_money=$this->html->readRQn('is_money');
    $action=$this->html->readRQ('action');
if ($table=='no_table') {
    $value=$this->data->save_from_inline();
    $out.= $value;
    exit;
}
    $sql="SELECT $field from $table where id=$id";
if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
    $fieldtype = pg_field_type($cur, 0);
if ((($fieldtype=='int4')||($fieldtype=='int2')||($fieldtype=='bool')||($fieldtype=='int8')||($fieldtype=='float8')||($fieldtype=='integer')||($fieldtype=='float'))) {
    $value=$this->utils->cleannumber($value, '.');
    $is_number=1;
}

    $sql="update $table set $field='$value' where id=$id";
    //$out.= "<pre>";print_r($sql);$out.= "</pre>";exit;
    $accessitemchk="edit_$table";
if ($action=='') {
    //allow some fields
    if ($field=='stamp_id') {
        $access[$accessitemchk]=1;
    }
    if ($field=='stamp_note') {
        $access[$accessitemchk]=1;
    }
    if (($access[$accessitemchk])) {
        $vals=array($field=>$value);
        $GLOBALS[record_old_vals]=$this->data->record_array($table, $id);
        $id=$this->db->update_db($table, $id, $vals);
        $this->data->DB_change($table, $id);
        //$out.= $sql;
    }
    if (($is_number)&&($is_money>0)) {
        $value=$this->utils->money($value);
    }
    $out.= $value;
}
echo $out;
exit;
//$body.=$out;
