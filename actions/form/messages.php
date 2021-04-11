<?php
//Edit messages
if ($act=='edit'){
    $sql="select * from $what WHERE id=$id";
    $res=$this->db->GetRow($sql);
}else{
    $sql="select * from $what WHERE id=$refid";
    $res2=$this->db->GetRow($sql);
    $res[active]='t';
}

$form_opt['well_class']="span11 columns form-wrap";

$out.=$this->html->form_start($what,$id,'',$form_opt);
$out.="<hr>";

$out.=$this->html->form_hidden('reflink',$reflink);
$out.=$this->html->form_hidden('id',$id);
$out.=$this->html->form_hidden('reference',$reference);
$out.=$this->html->form_hidden('refid',$refid);

$out.=$this->html->form_text('name',$res[name],'Name','',0,'span12');
$out.=$this->html->form_text('ref_name',$res[ref_name],'Ref name','',0,'span12');

$ref_id=$this->data->listitems('ref_id',$res[ref_id],'ref','span12');
$out.= "<label>".\util::l('ref')."</label>$ref_id";

$type_id=$this->data->listitems('type_id',$res[type_id],'type','span12');
$out.= "<label>".\util::l('type')."</label>$type_id";

$stage_id=$this->data->listitems('stage_id',$res[stage_id],'stage','span12');
$out.= "<label>".\util::l('stage')."</label>$stage_id";
$out.=$this->html->form_date('date',$res[date],'Date','',0,'span12');
$out.=$this->html->form_date('send_date',$res[send_date],'Send date','',0,'span12');

$user_id=$this->data->listitems('user_id',$res[user_id],'user','span12');
$out.= "<label>".\util::l('user')."</label>$user_id";
$out.=$this->html->form_textarea('descr',$res[descr],'Descr','',0,'','span12');
$out.=$this->html->form_textarea('addinfo',$res[addinfo],'Addinfo','',0,'','span12');
$out.=$this->html->form_text('message',$res[message],'Message','',0,'span12');
$out.=$this->html->form_text('data_json',$res[data_json],'Data json','',0,'span12');


$out.=$this->html->form_confirmations();
$out.=$this->html->form_submit('Save');
$out.=$this->html->form_end();

$body.=$out;
