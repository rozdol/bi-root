<?php
$tablename=$this->html->readRQ('tablename');
$tab=$this->html->readRQ('tab');
$destination=$this->html->readRQ('destination');
$descr=$this->html->readRQ('descr');
$addinfo=$this->html->readRQ('addinfo');
$forece_new=$this->html->readRQn('forece_new');
$return_to=$this->html->readRQ('return_to');
$return_to_id=$this->html->readRQn('return_to_id');
$return_to_act=$this->html->readRQ('return_to_act');
$return_back=$this->html->readRQn('return_back');
$amount=$this->html->readRQn('amount');
$amount_vat=$this->html->readRQn('amount_vat');
$currency=$this->html->readRQn('currency');
//$this->utils->log("-----Save Uplads of $tablename");
$json=json_encode(['POST'=>$_POST, 'GET'=>$_GET]);
//$this->utils->log("DropZone_save:$json");
// echo util::var_dump($_POST, TRUE,1,"POST");
// echo util::var_dump($_GET, TRUE,1,"GET");exit;
if($tablename=='save_file'){
	//echo "$tablename<br>";
	
	$link="?";
	
	$this->save('save_file');
	//echo " Saved.<br>". $this->html->link_button("<i class='icon-arrow-left icon-white'></i> Back",$link,'info')." ";
	//echo $this->html->refreshpage($link,0.1,'Saving...'); exit;
	exit;
	
}
if($tablename=='upload_file'){
	//$this->utils->log("----->>>upload_file");
	$this->save('upload_file');
	exit;
}
$refid=$this->html->readRQn('refid');
//$image_file=$dir=WWW_DIR.'unprotected'.DS.$newfname.'.jpg';
$reftype=$this->html->readRQn('reftype');
$reftype_alias=$this->html->readRQ('reftype_alias');
if(($reftype==0)&&($reftype_alias!='')){
	$reftype=$this->data->get_list_id('documents',$reftype_alias);
}
$related_data=json_decode($this->html->readRQ('related_data'),TRUE);


//echo $this->html->pre_display($related_data,'related_data'); echo $this->html->pre_display($_POST,'POST'); echo $this->html->pre_display($_GET,'POST'); echo "reftype:$reftype<br>"; exit;

$date=$GLOBALS[today];
if($tablename!='documents'){
	//make document
	$res=$this->db->GetRow("select * from $tablename where id=$refid");
	
	$doc_id=$this->db->GetVal("SELECT max(doc_id) from docs2obj where ref_id=$refid and ref_table='$tablename' and doc_id in (select id from documents where type=$reftype)")*1;
	
	if(($doc_id==0)||($forece_new>0)){
		//Insert new doc
		$name=$this->data->get_new_docname($date);
		$docgroup=$this->data->get_val('listitems','num1',$reftype)*1;
		if($docgroup==0){
			$docgroup_alias=$this->data->get_val('listitems','text2',$reftype);
			$docgroup=$this->db->getval("SELECT id from listitems where alias='$docgroup_alias'");
		}
		if($docgroup==0)$docgroup=1500;
		if($addinfo=='')$addinfo="Taken from $tablename ID:$refid";

		if($descr=='')$descr="Dropped from local computer";
		$vals=array(
			'name'=>$name,
			'parentid'=>$parentid*1,
			'type'=>$reftype,
			'docgroup'=>$docgroup,
			'creator'=>$GLOBALS[uid],
			'executor'=>$GLOBALS[uid],
			'date'=>$date,
			'datefrom'=>$date,
			'dateto'=>$date,
			'datecheck'=>$date,
			'qty'=>1,
			'amount'=>$amount,
			'amount_vat'=>$amount_vat,
			'currency'=>$currency,
			'uploads'=>0,
			'active'=>1,
			'complete'=>1,
			'descr'=>$descr,
			'addinfo'=>$addinfo
		);
		//echo $this->html->pre_display($_GET,'Post'); echo $this->html->pre_display($vals,'Vals');//exit;
		$doc_id=$this->db->insert_db('documents',$vals);

		$ref_type_id=0;
		$vals=array(
			'ref_id'=>$refid,
			'doc_id'=>$doc_id,
			'ref_table'=>$tablename,
			'type_id'=>$ref_type_id,
		);
		//echo $this->html->pre_display($vals,'ID: '.$id); //exit;
		if($refid>0)$this->db->insert_db('docs2obj',$vals);
		
		foreach($related_data as $key=>$value){
			//echo $this->html->pre_display($value,$key);
			foreach($value as $related_id){
				//echo "$key-$related_id<br>";
				$vals=array(
					'ref_id'=>$related_id,
					'doc_id'=>$doc_id,
					'ref_table'=>$key,
					'type_id'=>$ref_type_id,
				);
				$this->db->insert_db('docs2obj',$vals);
			}
		}

		$this->db->GetVar("insert into docs2groups (docid, groupid)values($doc_id,0)");
	}
	
	
	
	
	
	//add group
	
	
	
	$_POST['tablename']='documents';
	$_POST['refid']=$doc_id;
	$_POST['newfname']=$newfname;
	
	$_GET['tablename']='documents';
	$_GET['refid']=$doc_id;
	$_GET['newfname']=$newfname;
	
}
//$this->utils->log("Before Save");
$this->save('uploads');
//$this->utils->log("After Save");
if($return_to==''){
	$link="?act=details&what=$tablename&tab=$tab&id=$refid";
}else{
	if($return_to_act=='')$return_to_act='details';
	if($return_to_id=='')$return_to_id=$refid;

	$link="?act=$return_to_act&what=$return_to&tab=$tab&id=$return_to_id";
}
if(($return_back>0)&&($GLOBALS[reflink]!=''))$link=$GLOBALS[reflink];
echo " Saved.<br>". $this->html->link_button("<i class='icon-arrow-left icon-white'></i> Back",$link,'info')." ";
echo $this->html->refreshpage($link,0.1,'Saving...'); exit;

exit;