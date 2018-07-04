<?php
if (($what == 'config')&&($access['main_admin'])){
	$name=$this->html->readRQ('name');
	$id=$this->html->readRQ('id');
	$value=$this->html->readRQ('value');
	if ($id<>''){ 
		$query = "UPDATE $what set value='$value' where name='$id';";
		//$out.= "$query";
	}else{
		$id=$name;
		$query = "
		INSERT INTO config (
			name,
			value
		) VALUES(
			'$id',
			'$value'	
			);
		";   	
	}
	$result = $this->db->GetVar($query);
	$logtext.=" New value for config $id=$value";


	$logtext.=" name=$username fullname=$fullname";
}
/*
if($uid!=2)$dummy=$this->db->GetVal("insert into tableaccess (tablename,userid,date,time,refid,ip,descr)values('$what',$uid,now(),now(),$id,'$ip','$actname')");

$logtext=str_ireplace("'", "",$logtext);
$this->data->DB_log($logtext);

//----Log changes----
//if($this->data->field_exists($what, 'id')){}
if(($this->data->table_exists($what))&&($id>0))$GLOBALS['data_after_save']=$this->db->GetRow("select * from $what where id=$id");
$GLOBALS['data_diff_before_save']=array_diff_assoc($GLOBALS['data_before_save'],$GLOBALS['data_after_save']);
$GLOBALS['data_diff_after_save']=array_diff_assoc($GLOBALS['data_after_save'],$GLOBALS['data_before_save']);
//$data_diff_after_save=$GLOBALS['data_diff_after_save'];
$org_id=$id;
if(!is_numeric($org_id)){$org_id=0;}
$vals=array(
		'tablename'=>$what,
		'ref_id'=>$org_id,
		'user_id'=>$uid,
		'before'=>http_build_query($GLOBALS['data_diff_before_save']),
		'after'=>http_build_query($GLOBALS['data_diff_after_save']),
		'action'=>"$actname",
		'descr'=>"$actname T:$what, ID:$id, $userrec[firstname] $userrec[surname]"
	);
	//$out.= "<br>TEST:".http_build_query($data_diff_after_save)."<pre>";print_r($vals);$out.= "</pre>";$out.= "<pre>";print_r($GLOBALS);$out.= "</pre>";exit;
$this->db->insert_db('dbchanges',$vals);


//---notify users---
$notify_tables = array('partners', 'accounts', 'services', 'contracts','projects','clients');
if($what<>''){
	$text="$actname in $what with ID $id by $userrec[firstname] $userrec[surname] (".http_build_query($GLOBALS['data_diff_after_save']).")";
	if (in_array($what, $notify_tables))notify_users_chk($what,$id,$text);
}
*/
$body.=$out;
