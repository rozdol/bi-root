<?php
//echo $this->html->pre_display($_GET,'Get'); exit;
$ref_table=$this->html->readRQ('tablename');
if($ref_table=='')$ref_table=$this->html->readRQ('reference');
$ref_id=$this->html->readRQn('refid');
//if($ref_id==0)$ref_id=$this->html->readRQn('id');
$partner_id=$this->html->readRQn('partner_id');
//if($partner_id>0)$ref_id=0;

if ($act=='edit') {
    $sql="select * from $what WHERE id=$id";
    $res=$this->utils->escape($this->db->GetRow($sql));
    $link=$this->data->detalize($res[ref_table], $res[ref_id]);
    $partner=$this->data->detalize('partners', $res[partner_id]);
    $partner_id=$res[partner_id];
    $ref_table=$res[ref_table];
    $ref_id=$res[ref_id];
    $type_id=$this->data->listitems('type_id', $res[type_id], 'link_roles');
    $edit.= "<label>Type</label>$type_id";
    $join_to="Edit link of $doc to $link";
} else {
    $obj_id=$this->data->object_form('partners', 'partner_id', $partner_id, 'Partners');
    $type_id=$this->data->listitems('type_id', $res[type_id], 'link_roles');
    $ref_id=$refid;
    
    if (($partner_id==0)&&($ref_table!='')&&($ref_id>0)) { //connecting from oject
        $res=$this->db->GetRow("select * from $ref_table where id=$ref_id");
        $name=$res[name];
        
        $join_to.=  "Join $ref_table: $name to partner";
    } else {//connecting from partner
        $res=$this->db->GetRow("select * from partners where id=$partner_id");
        $name=$res[name];
        $join_to.=  "Join partner $name to $ref_table";
    }
}
$title=$this->html->readRQ('category');
if($title!='')$join_to=$title;
$form_opt['well_class']="span11 columns form-wrap";
$form_opt['title']="$join_to";

$out.=$this->html->form_start($what, 0, 'Partners', $form_opt);
$out.=$this->html->form_hidden('reflink', $reflink);
$out.=$this->html->form_hidden('id', $id);
if ($partner_id>0) {
    $out.=$this->html->form_hidden('partner_id', $partner_id);
}
if ($ref_table!='') {
    $out.=$this->html->form_hidden('ref_table', $ref_table);
}
if ($ref_id>0) {
    $out.=$this->html->form_hidden('ref_id', $ref_id);
}

$out.=$obj_id[out];
$out.=$obj_input;
$out.="<label>Role</label>$type_id</dt>";

$out.=$add."<hr>";
$out.=$this->html->form_submit('Save');
$out.=$this->html->form_end();

$out.= '				
<script>$(document).ready(function(){
	'.$obj_id[wait].'
	'.$obj_id[load].'	
});</script>
';

$body.=$out;
