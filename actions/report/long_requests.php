<?php
// report ling_requests
$days=$this->html->readRQn('days');
$days=($days==0)?5:$days;
$limit=$this->html->readRQn('limit');
$limit=($limit==0)?5:$limit;

$where="action like 'TIME:%'";
$sql="select count(*) from logs where $where";
//echo $sql;
$count=$this->db->GetVal($sql)*1;
if($count>0){
	$sql="select date_trunc('seconds', date) as date,userid, ip, action from logs where $where order by id desc limit $limit";
	$out.=$this->html->tag('Long requests','foldered');
	$fields=array('No','Time','IP','User','Seconds');
	$out.=$this->html->tablehead('','', '', '', $fields, '');
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	while ($row = pg_fetch_array($cur)) {
		$i++;
		$user=$this->data->username($row['userid']);
		$time=explode(',',$row['action']);
		$time=explode(':',$time[0]);
		$time=$time[1]*1;
		$out.= "<tr>";
		$action_org=$row['action'];
		$action_parts=explode('RQ:',$action_org);
		$action_arr=json_decode($action_parts[1]);
		$action_arr=isset($action_arr)?$action_arr:[];
		$link="?".http_build_query($action_arr);
		//echo $this->html->pre_display($action_arr,"action_arr $query");

		$row['action']=str_replace(array("\n","\r","\t","\""), array("<br>",""," ","|"), $row['action']);
		$row['action']=str_replace("|,","|<br>", $row['action']);
		$row['action']=str_replace("|","", $row['action']);
		$out.= "<td>$i</td><td onMouseover=\"showhint('$row[action]', this, event, '400px');\">$row[date]</td><td>$row[ip]</td><td>$user</td><td class='n'><a href='$link'>".$this->html->money($time)."</a></td></tr>\n";
	}
	$out.=$this->html->tablefoot($i,$totals,$totalrecs);
}
$body.= "$out";

