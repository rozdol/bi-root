<?php

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

$tmp=$this->html->readRQ('action');
if ($tmp!='') {
    $sql.=" and action='$tmp'";
}

$tmp=$this->html->readRQn('user_id');
if ($tmp!='') {
    $sql.=" and user_id=$tmp";
}

$sql1="select *";
$sql=" from $what a where id>0 $sql";
$sqltotal=$sql;
$sql = "$sql order by $sortby";
$sql2=" limit $limit offset $offset;";
$sql=$sql1.$sql.$sql2;
//$out.= $sql;
$fields=array('id','date','user','action','tablename','ref id');
//$sort= $fields;
$out.=$this->html->tablehead($what, $qry, $order, $addbutton, $fields, $sort);

if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
while ($row = pg_fetch_array($cur)) {
    $i++;
    $class='';
    $row[descr]=str_replace(array("\n","\r","\t"), array("<br>",""," "), $row[descr]);
    //$type=$this->data->get_name('listitems',$row[type]);
    $user=$this->db->GetVal("select u.firstname||' '||u.surname as user from users u where id=$row[user_id]");
    if ($row[id]==0) {
        $class='d';
    }
    $out.= "<tr class='$class'>";
    $out.= "<td>$i</td><td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
    $out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$row[date]</td>";
    $out.= "<td>$user</td><td>$row[action]</td><td>$row[tablename]</td><td>$row[ref_id]</td>";
    $out.=$this->html->HT_editicons($what, $row[id]);
    $out.= "</tr>";
    $csv.="$row[id]	$row[name]\t$row[descr]\n";
    $totals[2]+=$row[qty];
    if ($allids) {
        $allids.=','.$what.':'.$row[id];
    } else {
        $allids.=$what.':'.$row[id];
    }
}
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
