<?php
if ($access['main_admin']){
	$partnerid=$this->html->readRQ('id')*1;
	$df=$this->html->readRQd('df',1);
	$dt=$this->html->readRQd('dt',1);
	$partnername=$this->data->get_name("partners",$partnerid);
	$sql="select * from a_transactions where partnerid=$partnerid and date>='$df' and date<='$dt' order by date asc, sorting asc, id asc" ;
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	while ($row = pg_fetch_array($cur)) {
		$year=substr($row[date],8,2);
		if($prevyear!=$year){
			$i=0;
			$prevyear=$year;
		}
		$i++;
		$name=$year."-".sprintf("%04s",$i);
		if($row[name]!=$name){
			$this->db->GetVal("update a_transactions set name='$name' where id=$row[id]");
			$det=$this->data->detalize('a_transactions',$row[id]);
			$sql="update a_transactions set name='$row[name]' where id=$row[id];";
			$sql_array[]=$sql;
			echo "$row[name] => $name ($row[date]) $det<br>";
		}

	}
	$sqls=implode("\n", $sql_array);
	echo $this->html->pre_display($sqls,"To revert");
	echo "Transactions for $partnername are renumbered!";
}
?>