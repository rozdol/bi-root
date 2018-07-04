<?php
//Edit posts
if ($act=='edit'){
	$sql="select * from $what WHERE id=$id";
	$res=$this->db->GetRow($sql);
	
}else{
	$sql="select * from $what WHERE id=$refid";
	$res2=$this->db->GetRow($sql);
	$res[active]='t';
	$ref_table=$this->html->readRQ('ref_table','partners');
	$ref_id=$this->html->readRQn('ref_id',0,297);
}
$out.=$this->html->form_start($what,0,'');
$out.=$this->html->form_hidden('reflink',$reflink);
$out.=$this->html->form_hidden('id',$id);
$out.=$this->html->form_hidden('reference',$reference);
$out.=$this->html->form_hidden('refid',$refid);	
$out.=$this->html->form_hidden('ref_table',$ref_table);
$out.=$this->html->form_hidden('ref_id',$ref_id);					

$out.=$this->html->form_text('name',$res[name],'Title','',0,'span12');
$out.=$this->html->form_textarea('text',$res[text],'Post','',0,'','span12');

//$out.=$this->html->form_textarea('debug',"$ref_table $ref_id",'Title');

$out.=$this->html->form_submit('Save');
$out.=$this->html->form_end();

$body.=$out;
