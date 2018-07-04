<?php
if ($access['main_admin']){
	$partnerid=$this->html->readRQ('id')*1;
	$partnername=$this->data->get_name("partners",$partnerid);
	$sql="select * from a_transactions where partnerid=$partnerid order by date asc, sorting asc, id asc" ;
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}	
	while ($row = pg_fetch_array($cur)) {
		$year=substr($row[date],8,2);
		if($prevyear!=$year){
			$i=0;
			$prevyear=$year;
		}
		$i++;
		$name=$year."-".sprintf("%04s",$i);
		$this->db->GetVal("update a_transactions set name='$name' where id=$row[id]");
		echo "$row[name] => $name<br>";
	}
	echo "Transactions for $partnername are renumbered!";
}
?>