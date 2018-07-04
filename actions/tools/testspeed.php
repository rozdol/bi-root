<?php
if (!$access['main_admin'])$this->html->error('Honey pot');
if($id>0){
	$sql="select * from accounts order by id";
	$body.="<div class='green'>";
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	while ($row = pg_fetch_array($cur)) {
		$sum1=$this->db->GetVal("select sum(samount) from transactions where saccid=$row[id]")*1;
		$sum2=$this->db->GetVal("select sum(ramount) from transactions where raccid=$row[id]")*1;
		$sum1=$this->db->GetVal("select sum(samount) from transactions where saccid=$row[id]")*1;
		$sum2=$this->db->GetVal("select sum(ramount) from transactions where raccid=$row[id]")*1;
		$body.="$row[name]<br>";
	}
	$body.="</div>";
}
if($id==0){
	$sql="select * from accounts order by id limit 5 ";
	$body.="<div class='green'>";
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	while ($row = pg_fetch_array($cur)) {
		$sum1=$this->db->GetVal("select sum(samount) from transactions where saccid=$row[id]")*1;
		$sum2=$this->db->GetVal("select sum(ramount) from transactions where raccid=$row[id]")*1;
		$sum1=$this->db->GetVal("select sum(samount) from transactions where saccid=$row[id]")*1;
		$sum2=$this->db->GetVal("select sum(ramount) from transactions where raccid=$row[id]")*1;
		$body.="$row[name]<br>";
	}
	
}