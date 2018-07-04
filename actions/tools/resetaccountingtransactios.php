<?php
if (!$access['main_admin'])$this->html->error('Honey pot');
if ($access['main_admin']){
	$partnerid=$this->html->readRQ('id')*1;
	$partnername=$this->data->get_name("partners",$partnerid);
	$sql="delete from a_transactions where partnerid=$partnerid;";
	$this->db->GetVal($sql);
	echo "Accounting for $partnername is reset!";
}
?>