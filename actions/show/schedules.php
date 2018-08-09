<?php
if ($sortby=='') {
    $sortby="nextdate asc";
}
//if ($partnerid > 0){$sql = "$sql and  partnerid='$partnerid'";}
$filter = $this->html->readRQ("filter");
$tmp=($this->html->readRQ("refid")*1);
if ($tmp >0) {
    $sql = "$sql and refid=$tmp";
}
$tmp=($this->html->readRQ("userid")*1);
if ($tmp >0) {
    $sql = "$sql and userid=$tmp";
}
$tmp=($this->html->readRQ("tablename"));
if ($tmp !='') {
    $sql = "$sql and tablename='$tmp'";
}
$tmp=($this->html->readRQ("reference"));
if ($tmp !='') {
    $sql = "$sql and tablename='$tmp'";
}
if ($gid>3) {
    $sql = "$sql and (userid='$uid' or usersinvolved ~* '$uid,')";
}
$sql1="select id, name, tablename, nextdate, usersinvolved, type, userid, refid, descr, qty, active as act,
CASE WHEN a.active='t' THEN $$<img src='".ASSETS_URI."/assets/img/custom/ok.gif'>$$
ELSE $$<img src='".ASSETS_URI."/assets/img/custom/cancel.gif'>$$
END as active,

CASE WHEN a.confirm='t' THEN $$<img src='".ASSETS_URI."/assets/img/custom/ok.gif'>$$
ELSE $$<img src='".ASSETS_URI."/assets/img/custom/cancel.gif'>$$
END as confirm";
$sql=" from $what a where id>0 $sql";
$sqltotal=$sql;
$sql = "$sql order by $sortby";
$sql2=" limit $limit offset $offset;";
$sql=$sql1.$sql.$sql2;
//$out.= "$sql\n";
if ($this->db->GetVal("select count(*)".$sqltotal)==0) {
    $out.= "<div id='info'>No $what.</div>";
    return;
}


    $fields=array('id','name','table','ref','type','qty','next','by','Involved','C');
    //$sort= $fields;
    $out.=$this->html->tablehead($what, $qry, $order, $addbutton, $fields, $sort);
    
$nbrow=0;
$i=$limit*$page;
if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
$rows=pg_num_rows($cur);
$csv.=$this->data->csv($sql);
while ($row = pg_fetch_array($cur)) {
    $nbrow++;
    $i=$i+1;
    $no=sprintf("%03s", $row[id]);
    $col_col = "";
    if ($row[id]==$recentid) {
        $col_col = "h";
    }
    if ($row[act]=='f') {
        $col_col = "d";
    }
    //$row[usersinvolved]=substr($row[usersinvolved],0,-2);
    $users="";
    $row[usersinvolved]=$this->utils->normalize_list($row[usersinvolved], ',');
    if ($row[usersinvolved]!='') {
        $usersinvolved=explode(',', $row[usersinvolved]);
        $usersinvolved2=array();
        foreach ($usersinvolved as $user_involved) {
            $user_involved=trim($user_involved);
            if ($user_involved!='') {
                $usersinvolved2[]=$user_involved;
            }
        }
        $row[usersinvolved]=implode(',', $usersinvolved2);
        $users=$this->utils->normalize_list($this->utils->F_tostring($this->db->GetResults("select username from users where id in ($row[usersinvolved]);")));
    }
    $reference=$this->db->GetVal("select '<a href=\"?act=details&what=$row[tablename]&id=$row[refid]\">'||name||'</a>' from $row[tablename] where id=$row[refid];");
    $type=$this->db->GetVal("select name from listitems where id=$row[type]");
    $user=$this->db->GetVal("select username from users where id=$row[userid]");
    $weekday=$this->dates->F_weekdayname($row[nextdate]);
    $redir="";
    //if(($row[tablename]=='transactions')&&($uid==19))$redir="<a href='?csrf=$GLOBALS[csrf]&act=save&what=sched2account&id=$row[id]&to=saccid'>[S]</a> - <a href='?csrf=$GLOBALS[csrf]&act=save&what=sched2account&id=$row[id]&to=raccid'>[R]</a>";
    if (($weekday=='Sun')||($weekday=='Sat')) {
        $weekday="<b>$weekday</b>";
    }
    $out.= "\t<tr  class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">\n";
    $out.="  <td>$i</td><td>$no</td>";
    $out.= "\t\t<td onMouseover=\"showhint('$row[descr]', this, event, '400px')\">$row[name]</td>\n";
    $out.= "
		<td>$row[tablename]</td>
	<td>$reference</td>
	<td>$type</td>
	<td>$row[qty]</td>
	<td>$row[nextdate] ($weekday)</td>

	<td>$user</td>
	<td>$users</td>
	<td>$row[confirm]</td>

	";
    $out.= $this->html->HT_editicons($what, $row[id]);
    $out.= "\t</tr>\n";
    $totals[0]+=1;
}

include(FW_DIR.'/helpers/end_table.php');
