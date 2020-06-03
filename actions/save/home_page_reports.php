<?php
$post_data=$_POST;

$sql="SELECT r.name, r.descr, r.link, hp.active, hp.sorting, r.id as report_id ,hp.id as homepage_id
FROM reports r
LEFT JOIN homepages hp ON r.id=hp.report_id
WHERE hp.user_id=$GLOBALS[uid]
ORDER BY hp.active DESC, hp.sorting ASC, r.name ASC";

$inline_edit=1;
if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);$start_time=$this->utils->get_microtime();
while ($row = pg_fetch_array($cur,NULL,PGSQL_ASSOC)){
	$sorting_name='sorting_'.$row[homepage_id];
	$active_name='active_'.$row[homepage_id];

	$sorting_data[$sorting_name]=$this->html->readRQn($sorting_name);
	$active_data[$active_name]=$this->html->readRQn($active_name);
}



foreach ($sorting_data as $key => $value) {
	$parts=explode('_',$key);
	$sorting_id=$parts[1]*1;
	$this->db->update_db("homepages",$sorting_id,['sorting'=>$value]);
}

foreach ($active_data as $key => $value) {
	$parts=explode('_',$key);
	$active_id=$parts[1]*1;
	$this->db->update_db("homepages",$active_id,['active'=>$value]);
}

