<?php
$tablename=$what;
if ($what == $tablename) {
    $sql="SELECT * FROM $tablename ";
    //echo $sql;
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $res=$this->db->GetRow("select * from $what where id=$id; -- default details");
    //$partner=$this->data->detalize('partners', $res[partner_id]);
    $date=$this->dates->F_date($this->html->readRQ('date'), 1);
    $out.= "<h1>$res[name]</h1>\n";
    $out.=$this->data->details_bar($what, $id);
    $out.= "<table class='table table-morecondensed table-notfull'>";

    $i = pg_num_fields($cur);
    for ($j = 0; $j < $i; $j++) {
        //echo "column $j\n";
        $fieldname = pg_field_name($cur, $j);
        $fieldtype = pg_field_type($cur, $j);
        //$out.= "F:$fieldname:$fieldtype\n";
        $ok="";

        if ((($fieldtype=='int4')||($fieldtype=='int2')||($fieldtype=='bool')||($fieldtype=='int8')||($fieldtype=='float8')||($fieldtype=='integer')||($fieldtype=='float'))&&($ok=="")) {
            $out.="<tr><td class='mr'><b>".str_replace('_', ' ', ucfirst($fieldname)).": </b></td><td class='mt'>$res[$fieldname]</td></tr>";
        } else {
            $out.="<tr><td class='mr'><b>".str_replace('_', ' ', ucfirst($fieldname)).": </b></td><td class='mt'>$res[$fieldname]</td></tr>";
        }

        if ($fieldname!='id') {
            //$out4.="\t\t'$fieldname'=>$fieldname,\n";
        }
    }

    $out.="</table>";
    if ($res[descr]) {
        $out.= "Description:<br><pre>$res[descr]</pre>";
    }


    if ($this->data->table_exists('documents')) {
        $dname=$this->data->docs2obj($id, $what);
        $out.="<b>Documents:</b> $dname<br>";
        $out.=$this->show_docs2obj($id, $what);
    }
    
    $_POST[noadd]='';

    $_POST[tablename]=$what;
    $_POST[refid]=$id;
    $_POST[reffinfo]="&tablename=$what&refid=$id";
    if ($this->data->table_exists('schedules')) {
        $out.=$this->show('schedules');
    }
    if ($this->data->table_exists('schedules')) {
        $out.=$this->show('comments');
    }
    
    $_POST[tablename]=$what;
    $_POST[ref_id]=$id;
    
    
    //$out.= $this->html->show_hide('Table Access', "?act=show&what=tableaccess&plain=1&tablename=$what&refid=$id");
}
$body.="$out $nav $export";
