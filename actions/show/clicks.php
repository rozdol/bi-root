<?php
//Show clicks
if ($sortby=='') {
    $sortby="id desc";
}

$tmp=$this->html->readRQcsv('ids');
if ($tmp!='') {
    $sql.=" and id in ($tmp)";
}

$tmp=$this->html->readRQn('ref_id');
if ($tmp>0) {
    $sql.=" and ref_id=$tmp";
}

$tmp=$this->html->readRQn('uid');
if ($tmp>0) {
    $sql.=" and uid=$tmp";
}

$tmp=$this->html->readRQ('ip');
if ($tmp!='') {
    $sql.=" and ip = '$tmp'";
}

$tmp=$this->html->readRQ('uname');
if ($tmp!='') {
    $sql.=" and uname='$tmp'";
}

$tmp=$this->html->readRQ('refference');
if ($tmp!='') {
    $sql.=" and what='$tmp'";
}

$tmp=$this->html->readRQ('action');
if ($tmp!='') {
    $sql.=" and act='$tmp'";
}

$tmp=$this->html->readRQd('df');
if ($tmp!='') {
    $sql.=" and date>='$tmp'";
}
$tmp=$this->html->readRQd('dt');
if ($tmp!='') {
    $sql.=" and date<='$tmp'";
}

$tmp=explode(',', $this->html->readRQcsv('tags', '', 0));
if (count($tmp)>0) {
    foreach ($tmp as $value) {
        $sql.=" and (lower(post) like lower('%$value%') or  lower(get) like lower('%$value%'))";
    }
}


$sql1="select *";
$sql=" from $what a where id>0 $sql";
$sqltotal=$sql;
$sql = "$sql order by $sortby";
$sql2=" limit $limit offset $offset;";
$sql=$sql1.$sql.$sql2;
//$out.= $sql;
$fields=array('id','date','time','ip','uname','act','what','ref_id','get','post',);
//$sort= $fields;
$out.=$this->html->tablehead($what, $qry, $order, 'no_addbutton', $fields, $sort);
        
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
    //$type=$this->data->get_name('listitems',$row[type]);
    if ($row[id]==0) {
        $class='d';
    }
    $out.= "<tr class='$class'>";
    $out.= $this->html->edit_rec($what, $row[id], 'ved', $i);
    $out.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
    //$out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$row[name]</td>";
    $action='details';
    if (in_array($row[act], ['pdf','report'])) {
        $action=$row[act];
    }
    if (in_array($row[act], ['append'])) {
        $row[ref_id]=0;
    }
    if ($row[ref_id]>0) {
        $row[ref_id]="<a href='?act=$action&what=$row[what]&id=$row[ref_id]'>$row[ref_id]</a>";
    }
    $time=$this->dates->F_date_spilt($row[date]);
    $out.= "<td>$time[date]</td>";
    $out.= "<td>$time[time]</td>";
    $out.= "<td>$row[ip]</td>";
    $out.= "<td>$row[uname]</td>";
    $out.= "<td>$row[act]</td>";
    $out.= "<td>$row[what]</td>";
    $out.= "<td>$row[ref_id]</td>";
    //$out.= "<td>$row[get]</td>";
    //$out.= "<td>$row[post]</td>";

    $get=json_decode($row[get], true);
    if (count($get)>0) {
        $get=str_ireplace("'", "\'", $this->html->array_nested_display($get));
        $out.="<td ckass='n' onMouseover=\"showhint('$get', this, event, '400')\"><i class='icon-info-sign'></i></td>";
    } else {
        $get='';
        $out.="<td> </td>";
    }

    $post=json_decode($row[post], true);
    if (count($post)>0) {
        $link='';
        if (($post[id]==0)&&($post[name]!='')&&($this->data->field_exists($row[what], 'name'))) {
            $ids=$this->data->get_list_csv("SELECT id from $row[what] where name like '%$post[name]%' order by id desc limit 1");
            $link="<a href='?act=details&what=$row[what]&id=$ids'><i class='icon-eye-open'></i></a>";
        }

        $post=str_ireplace("'", "\'", $this->html->array_nested_display($post));
        $out.="<td ckass='n' onMouseover=\"showhint('$post', this, event, '400')\"><i class='icon-info-sign'></i> $link</td>";
    } else {
        $post='';
        $out.="<td> </td>";
    }

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
