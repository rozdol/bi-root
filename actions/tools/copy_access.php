<?php
if ($access['main_admin']){
	//?act=tools&what=copy_access&group_id_from=3&group_id_to=31&gain=1
	$gain=$this->html->readRQn('gain');
	$group_id_from=$this->html->readRQn('group_id_from');
	$group_id_to=$this->html->readRQn('group_id_to');
	$group_id_tos=$this->html->readRQcsv('group_id_tos');
	$group_id_tos=explode(',', $group_id_tos);
	if($group_id_to>0)$group_id_tos[]=$group_id_to;
	$group_id_tos=array_filter($group_id_tos, function($value) { return $value !== ''; });
	//echo $this->html->pre_display($group_id_tos,"group_id_tos"); exit;
	foreach ($group_id_tos as $group_id_to) {
		$from=$this->data->get_name('groups',$group_id_from);
		$to=$this->data->get_name('groups',$group_id_to);
		$this->livestatus($this->html->tag("Copy access from $from to $to",'h5',''));
		//echo $this->html->tag("Copy access from $from to $to",'h5','');
		if($gain==0)$this->db->GetVal("update accesslevel set access=0 where groupid=$group_id_to");
		$sql="select * from accesslevel where groupid=$group_id_from order by accessid" ;
		if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}	
		while ($row = pg_fetch_array($cur)) {
			$r++;
			$accessname=$this->data->get_name('accessitems',$row[accessid]);
			$copy=1;
			$was=$this->db->getval("select access from accesslevel where groupid=$group_id_to and accessid=$row[accessid]");
			if (strlen(strstr($accessname,"user"))>0) $copy=0;
			if (strlen(strstr($accessname,"group"))>0) $copy=0;
			if (strlen(strstr($accessname,"admin"))>0) $copy=0;
			if (strlen(strstr($accessname,"access"))>0) $copy=0;
			if (strlen(strstr($accessname,"config"))>0) $copy=0;
			if (strlen(strstr($accessname,"debug"))>0) $copy=0;
			if (strlen(strstr($accessname,"docact2users"))>0) $copy=1;
			if (strlen(strstr($accessname,"useralerts"))>0) $copy=1;
			if (strlen(strstr($accessname,"report_"))>0) $copy=1;
			if (strlen(strstr($accessname,"main_access"))>0) $copy=1;

			if(($copy>0)&&($gain==0))$this->db->GetVal("update accesslevel set access=$row[access] where groupid=$group_id_to and accessid=$row[accessid]");
			if(($copy>0)&&($gain==1)&&($row[access]==1))$this->db->GetVal("update accesslevel set access=$row[access] where groupid=$group_id_to and accessid=$row[accessid]");

			if(($row[access]==1)&&($was==0)){$a++;echo "$a $accessname ($row[access]) $from -> $to<br>";}
		}
	}
	$this->livestatus('');
}