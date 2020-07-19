<?php

if (($what == 'createaccounting')&&($access['edit_a_accounts'])){
	$id=$this->html->readRQn('id');
	//$pname=$db->GetVal("select newaccounting($id)");
	$sql="delete from a_accounts where partnerid=$id;
	delete from a_transactions where partnerid=$id;";
	$this->db->GetVal($sql);
	$curr_id=$this->project->new_accounting($id);
	$pname=$this->data->get_name('partners',$id);
	echo"<div class='floatbox'>Accounting for $pname is Initialized.<br><a href='?act=report&what=accounting&id=$id'>Go to accounting</a></div>";
	//echo $this->html->refreshpage("?act=report&what=accounting&id=$id",2,"Accounting for $pname is Initialized.","INFO",'alert alert-info');
}