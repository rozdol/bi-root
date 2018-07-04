<?php
if ($what == 'lookup'){
	$edit=$this->html->readRQ('edit');
	$child=$this->html->readRQ('child');
	$role=$this->html->readRQ('role');
	$acctype=$this->html->readRQn('acctype');
	$contract_id=$this->html->readRQn('contract_id');//$refid=1;
	$loan_id=$this->html->readRQn('loan_id');
	$id=$this->html->readRQn('id');
	if($field=='')$field='partner';
	if(($contract_id>0)&&($refid==0)){
		if($role=='r')$refid=$this->db->GetVal("select seller_id from contracts where id=$contract_id");
		if($role=='s')$refid=$this->db->GetVal("select buyer_id from contracts where id=$contract_id");		
	}
	if(($loan_id>0)&&($refid==0)){
		if($role=='r')$refid=$this->db->GetVal("select receiver_id from loans where id=$loan_id");
		if($role=='s')$refid=$this->db->GetVal("select sender_id from loans where id=$loan_id");		
	}
	
	if($table=='partners'){
		$sql="select count(*) from $table WHERE lower(name) like lower('%$value%') or lower(ru) like lower('%$value%') or lower(en) like lower('%$value%') or lower(synonyms) like lower('%$value%')";
	}else{
		$sql="select count(*) from $table WHERE lower(name) like lower('%$value%')";
	}
	$count=$this->db->GetVal($sql);
	if($count>0){
		if($table=='partners'){
			$sql="SELECT id, name FROM $table WHERE lower(name) like lower('%$value%') or lower(ru) like lower('%$value%') or lower(en) like lower('%$value%') or lower(synonyms) like lower('%$value%') ORDER by name";
		}else{
				if($this->data->field_exists($table,'active')){$addsql="|| CASE WHEN active='f' THEN $$ Not Active $$ ELSE $$$$ END, CASE WHEN active='f' THEN $$ background-color: #bbb; $$ ELSE $$$$ END";}
			$sql="SELECT id, name $addsql FROM $table WHERE lower(name) like lower('%$value%') ORDER by name";
		}
		if($edit!=''){$sql="SELECT id, name FROM $table WHERE id=$refid ORDER by name"; $id=$refid;}
		if($child=='account'){
			if($role=='s')$varname='sender_id';	
			if($role=='r')$varname='receiver_id';
			if($role=='f')$varname='frompartner_id';
			if($role=='t')$varname='topartner_id';		  
			$response=$this->html->htlist($varname,$sql,$id,'Select partner',"onchange='itemid=this.options[this.selectedIndex].value;
			ajaxFunction(\"".$role."_acc_id_\",\"?csrf=$GLOBALS[csrf]&act=append&what=acc_id&role=$role&acctype=$acctype&refid=\"+itemid);'");
		}
		if($child=='voyage'){
			$varname='voyage_id';		  
			$response=$this->html->htlist($varname,$sql,$id,'Select',"onchange='itemid=this.options[this.selectedIndex].value;
			ajaxFunction(\"voyage_id_\",\"?csrf=$GLOBALS[csrf]&act=append&what=voyages&refid=\"+itemid);'");
		}
		if($child==''){						  
			$response=$this->html->htlist($field,$sql,$id,'',"onchange='itemid=this.options[this.selectedIndex].value;',$id");
		}
		
	}else{
		$response="No records found for <b>$value</b>";
	}
	$out.= "$response";
}


$body.=$out;
