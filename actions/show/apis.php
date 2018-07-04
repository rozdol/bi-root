<?php
//Show apis
if ($sortby=='') {
    $sortby="id asc";
}

$span=$this->html->readRQ('span');
if ($this->utils->contains('my', $span)) {
    $sql.=" and user_id=$GLOBALS[uid]";
}
if ($this->utils->contains('users', $span)) {
    $sql.=" and user_id=".$id;
}

$tmp=$this->html->readRQcsv('ids');
if ($tmp!='') {
    $sql.=" and id in ($tmp)";
}

//$sql.=" and user_id=$GLOBALS[uid]";

$sql1="select *";
$sql=" from $what a where id>0 $sql";
$sqltotal=$sql;
$sql = "$sql order by $sortby";
$sql2=" limit $limit offset $offset;";
$sql=$sql1.$sql.$sql2;
//$out.= $sql;
$fields=array('#','user','date exp','functions','key','');
//$sort= $fields;
$out=$this->html->tablehead('', $qry, $order, 'no_addbutton', $fields, $sort);
//$_POST[noedit]=1;
//$_POST[nodelete]=1;
$_POST[noview]=1;
if (!($cur = pg_query($sql))) {
    $this->html->HT_Error(pg_last_error()."<br><b>".$sql."</b>");
}
$rows=pg_num_rows($cur);
if ($rows>0) {
    $csv.=$this->data->csv($sql);
}
while ($row = pg_fetch_array($cur)) {
    $i++;
    $class='';
    $user=$this->data->username($row[user_id]);
    //$phys=($row[physical]=='t')?"Y":"N";
    if ($row[id]==0) {
        $class='d';
    }
    $out.= "<tr class='$class'>";
    //$out.= $this->html->edit_rec($what,$row[id],'ed',$i);
    $out.= "<td>$i</td>";
    //$out.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
    $out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$user</td>";
    $out.= "<td>$row[exp_date]</td>";
    $out.= "<td>$row[functions]</td>";
    $out.= "<td>$row[key]</td>";
    $out.=$this->html->HT_editicons($what, $row[id]);
    $out.= "</tr>";
    $totals[2]+=$row[qty];
    if ($allids) {
        $allids.=','.$what.':'.$row[id];
    } else {
        $allids.=$what.':'.$row[id];
    }
    $this->livestatus(str_replace("\"", "'", $this->html->draw_progress($i/$rows*100)));
}
$this->livestatus('');
include(FW_DIR.'/helpers/end_table.php');
