<?php
if (($GLOBALS[access][main_admin])||($GLOBALS[access][accounting_reset])) {
	$partnerid=$this->html->readRQ('id')*1;
	$partnername=$this->data->get_name("partners",$partnerid);
	$sql="delete from a_accounts where partnerid=$partnerid;
	delete from a_transactions where partnerid=$partnerid;";
	$this->db->GetVal($sql);
	//echo "Accounting for $partnername is reset!<br><a href='?act=details&what=partners&id=$id'>Go to Partner</a>";
	echo $this->html->refreshpage("?act=details&what=partners&id=$id",2,"Accounting for $partnername is Deleted!","INFO",'alert alert-info');
}