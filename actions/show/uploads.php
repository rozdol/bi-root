<?php

$refid=($this->html->readRQ("refid")*1);
if($refid==0){$out.= "No uploads"; exit;}
//$out.= "<a href='?act=add&what=multyuploads&refid=$refid&tablename=documents'><i class='btn btn-info btn-mini'>Multiple upload</i></a>";
//$out.= "<a href='?act=add&what=webcam&refid=$refid&tablename=documents'><i class='btn btn-info btn-mini'>Take from camera</i></a>";

//$out.= $this->html->link_button("<i class='icon-download icon-white'></i> Multiple upload","?act=add&what=multyuploads&refid=$refid&tablename=documents",'info')." ";
$out.= $this->html->link_button("<i class='icon-camera icon-white'></i> Take from camera","?act=add&what=webcam&refid=$refid&tablename=documents",'info')." ";

$document=$this->db->GetRow("select * from documents where id=$refid");
if($sortby==''){$sortby="u.date desc";}
//$reftype=($this->html->readRQ('reftype'))*1;
$tmp=($this->html->readRQ("text"));	
if ($tmp <> ''){$sql = "$sql and  (u.tags ~* '$tmp' or u.name ~* '$tmp' or u.descr ~* '$tmp')"; $titlestr.=" witch contains '".$tmp."'";}
$tmp=($this->html->readRQ("tablename"));	
if ($tmp <> ''){$sql = "$sql and  u.tablename='$tmp'"; $tablename=$tmp;$titlestr.=" witch contains '".$tmp."'";}
$tmp=($this->html->readRQ("refid")*1);	
if ($tmp <> ''){$sql = "$sql and  u.refid=$tmp"; $titlestr.=" witch contains '".$tmp."'";}
$tmp=($this->html->readRQ("showdeleted")*1);	
if ($tmp==0){$sql = "$sql and u.active='t'";}
$opt=($this->html->readRQ('opt'));
//$sql="SELECT u.id, u.thumb, u.name, u.descr from uploads u where u.tablename='$tablename' and u.userid=$uid and u.refid=$refid order by u.name;";
$sql="SELECT u.id, u.thumb, u.name, u.date, (u.filesize/1000)||'Kb' as Size, substr(u.descr,0,1000), us.username from uploads u, users us where us.id=u.userid and u.id>0 $sql order by u.date desc, u.id desc;";
//if(($opt=='all')&&($access['main_admin'])){$sql="SELECT u.id, u.thumb, us.firstname||' '||us.surname as user, u.tablename, u.name, u.descr from uploads u, users us where u.userid=us.id order by $sortby;";}
$select_data = $this->db->GetResults($sql);
//$sql="SELECT u.id, u.thumb, us.firstname||' '||us.surname as user, u.tablename, u.name, u.descr from uploads u, users us where u.userid=us.id order by $sortby;";
//$out.= "$sql";
$fields=array('id','name','date','size','descr','username','link');
//$sort= $fields;
$out.=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);

if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);
$csv.=$this->data->csv($sql);
while ($row = pg_fetch_array($cur)) {
	$i++;
	$class='';
	if($row[id]==0)$class='d';
	$out.= "<tr class='$class'>";
	$out.= "  <td>$i</td><td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
	$out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$row[name]</td><td>$row[date]</td><td>$row[size]</td><td>$row[substr]</td><td>$row[username]</td>";
	if($document[block_download]=='f'){
		$out.= "<td><a href='?act=details&what=uploads&id=$row[id]'><img src='".ASSETS_URI."/assets/img/download.png'></a></td>";
		$out.=$this->html->HT_editicons($what, $row[id]);
	}else{
		if(($access[main_admin])||($access[block_download])){
			$out.= "<td><a href='?act=details&what=uploads&id=$row[id]'><img src='".ASSETS_URI."/assets/img/download.png'></a></td>";
			$out.=$this->html->HT_editicons($what, $row[id]);
		}
	}
	$out.= "</tr>";
	$csv.="$row[id]	$row[name]\t$row[descr]\n";
	$totals[2]+=$row[qty];
	if ($allids) $allids.=','.$what.':'.$row[id]; else $allids.=$what.':'.$row[id];	
	//$responce.= "<a href='?act=details&what=uploads&id=$id'><img src='".ASSETS_URI."/assets/img/download.png'></a>";
}
include(FW_DIR.'/helpers/end_table.php');
//$out.= "<a href='?act=add&what=importtrips'>Import</a> .::. <a href='?act=tools&what=export	trips'>Export</a>"; 


