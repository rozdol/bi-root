<?php
if ($this->data->table_exists($what)) {
    if ($sortby=='') {
        $sortby="id asc";
    }
    $sql="select * from $what limit 1";
    $fields=array();
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $num_fields = pg_num_fields($cur);
    $fielsd_a=array();
    for ($j = 0; $j < $num_fields; $j++) {
        $fieldname = pg_field_name($cur, $j);
        $fieldtype = pg_field_type($cur, $j);
        if ($fieldname!='descr') {
            array_push($fields, $fieldname);
        }

        $items = array("$fieldname" => "$fieldtype");
        $this->utils->array_push_associative($fields_a, $items);
    }

    $sql="";

    foreach ($_REQUEST as $key => $value) {
        //echo "$key => $value<br>";
        $ok=0;
        if (($value!="")&&(in_array($key, $fields))) {
            if (($fields_a[$key]=='int4')||($fields_a[$key]=='int8')||($fields_a[$key]=='float8')) {
                $tmp=$this->html->readRQn($key);
                if ($tmp>0) {
                    $sql.=" and $key=$tmp";
                }
                $ok=1;
            }
            if (($fields_a[$key]=='date')||($fields_a[$key]=='datetime')||($fields_a[$key]=='timestamp')) {
                $tmp=$this->html->readRQ($this->dates->F_date($key, 1));
                if ($tmp!='') {
                    $sql.=" and $key='$tmp'";
                }
                $ok=1;
            }
            if ($ok==0) {
                $tmp=$this->html->readRQ($key);
                if ($tmp!='') {
                    $sql.=" and lower($key) like lower('%$tmp%') ".$fields_a[$key];
                }
            }
        }
    }
    //exit;

    $sql1="select *";
    $sql=" from $what where id>0 $sql";
    $sqltotal=$sql;
    $sql = "$sql order by $sortby";
    $sql2=" limit $limit offset $offset;";
    $sql=$sql1.$sql.$sql2;

    //$out.= $sql;
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $rows=pg_num_rows($cur);
    if ($rows>0) {
        $csv.=$this->data->csv($sql);
        $sort= $fields;
        $out.=$this->html->tablehead($what, $qry, $order, $addbutton, $fields, $sort);
        while ($row = pg_fetch_array($cur)) {
            $i++;
            $n++;
            $class='';
            //$type=$this->data->get_name('listitems',$row[type]);
            if ($row[id]==0) {
                $class='d';
            }
            $out.= "<tr class='$class'>";
            $out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$n</td>";
            for ($j = 0; $j < $num_fields; $j++) {
                $fieldname = pg_field_name($cur, $j);
                $fieldtype = pg_field_type($cur, $j);
                $printed=0;
                if ($j==0) {
                    $out.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
                }
                if ($j>0) {
                    if (($printed==0)&&(($fieldtype=='int4')||($fieldtype=='int8')||($fieldtype=='date'))) {
                        $out.= "<td  class='n'>$row[$fieldname]</td>";
                        $printed=1;
                    }
                    if (($printed==0)&&($fieldtype=='float8')) {
                        $out.= "<td  class='n'>".$this->html->money($row[$fieldname])."</td>";
                        $printed=1;
                    }
                    if (($printed==0)&&($fieldname=='descr')) {
                        $printed=1;
                    }
                    if ($printed==0) {
                        $out.= "<td>$row[$fieldname]</td>";
                        $printed=1;
                    }// ($fieldtype)
                }
            }
            $out.=$this->html->HT_editicons($what, $row[id]);
            $out.= "</tr>";
            if ($allids) {
                $allids.=','.$what.':'.$row[id];
            } else {
                $allids.=$what.':'.$row[id];
            }
            $this->livestatus(str_replace("\"", "'", $this->html->draw_progress($i/$rows*100)));
        }
        $this->livestatus('');
        include(FW_DIR.'/helpers/end_table.php');
        if (($GLOBALS[access][main_admin])&&($GLOBALS[access][view_debug])) {
            $body.=$this->html->tag("<a href='?act=tools&what=genform&tablename=$what'>Generate code</a>", "span", "btn");
        }
    }
} else {
    $out.=$this->html->error($this->html->message("Object '$what' not found.", "404", 'alert-error'));
}
