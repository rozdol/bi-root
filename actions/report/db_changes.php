<?php

$_POST[nodelete]=1;
$_POST[noedit]=1;
if ($sortby=='') {
    $sortby="id asc";
}

$tmp=$this->html->readRQn('ref_id');
if ($tmp>0) {
    $sql.=" and ref_id=$tmp";
}
$tmp=$this->html->readRQn('refid');
if ($tmp>0) {
    $sql.=" and ref_id=$tmp";
}
$tmp=$this->html->readRQ('tablename');
if ($tmp!='') {
    $sql.=" and tablename='$tmp'";
}

$tmp=$this->html->readRQ('refference');
if ($tmp!='') {
    $sql.=" and tablename='$tmp'";
}

$sql1="select *";
$sql=" from dbchanges a where id>0 $sql";
$sqltotal=$sql;
$sql = "$sql order by id desc";
$sql2=" limit $limit offset $offset;";
$sql=$sql1.$sql.$sql2;
//$out.= $sql;
$fields=array('#','id','date','user','action','tablename','Restore','Cahnges',' ');
//$sort= $fields;
$out.=$this->html->tag('Trail changes', 'foldered');
$out.=$this->html->tablehead('', '', '', '', $fields);
$GLOBALS['access']['edit_dbchanges']=0;

if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
while ($row = pg_fetch_array($cur)) {
    $i++;
    $class='';
    $row[date]=substr($row[date], 0, 16);
    $row[descr]=str_replace(array("\n","\r","\t"), array("<br>",""," "), $row[descr]);
    $obj=$this->data->detalize($row[tablename], $row[ref_id]);
    //$type=$this->data->get_name('listitems',$row[type]);
    $user=$this->data->get_user_info($row[user_id]);
    $username=$user[full_name];
    $row[changes]=htmlspecialchars($row[changes]);
    $changes=$row[changes];
    $row[changes]=json_decode($row[changes], true);

    
    //echo $this->html->pre_display($row[changes], "changes");
    //$changes=$this->html->array_display($row[changes]);
    if ($row[id]==0) {
        $class='d';
    }

    if ($GLOBALS[access][edit_restore_snapshot]) {
        $restore=$this->html->link_button("<i class='icon-repeat'></i>", "?csrf=$GLOBALS[csrf]&act=save&what=restore_snapshot&id=$row[id]", 'btn-mini2', "Are you sure you want to restore $row[tablename] to these values?");
    }
                  
    $out.= "<tr class='$class'>";
    $out.= "<td>$i</td><td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
    $out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$row[date]</td>";
    $out.= "<td>$username</td>
			<td onMouseover=\"showhint('$changes', this, event, '400px');\">$row[action]</td>
			<td>$obj</td><td>$restore</td><td>$changes</td>";
    $out.=$this->html->HT_editicons('dbchanges', $row[id]);
    $out.= "</tr>";
    $csv.="$row[id]	$row[name]\t$row[descr]\n";
    $totals[2]+=$row[qty];
    if ($allids) {
        $allids.=','.$what.':'.$row[id];
    } else {
        $allids.=$what.':'.$row[id];
    }
}
unset($_POST[nodelete]);
unset($_POST[noedit]);

$out.=$this->html->tablefoot($i, $totals, $totalrecs);
$totals=$this->utils->F_toarray($this->db->GetResults("select count(*)".$sqltotal));
if ($dynamic>0) {
    $nav=$this->html->HT_ajaxpager($totals[0], $orgqry, "$titleorig.");
} else {
    $nav=$this->html->HT_pager($totals[0], $orgqry);
}
if ($i>5) {
    $nav.= $this->html->add_all_to_cart2($what);
}
if ($noexport=='') {
    $export= $this->utils->exportcsv($csv);
}
$body.= "$out $nav $export";
