<?php 
$type_id=$this->data->listitems('type_id', $res[type_id], 'means');
$form_opt['url']="?act=show&what=messages";
$form_opt['title']="Search in sent messages report";
$form_opt['well_class']="span11 columns form-wrap";
$out.=$this->html->form_start($what,$id,'d',$form_opt);
$out.="<hr>";
$out.="<label>Means of transfer</label>$type_id";
$out.=$this->html->form_text('text', '', 'Text in message', '', 0, 'span12');
$out.=$this->html->form_text('attachment', '', 'Text in name of attachment', '', 0, 'span12');
$out.=$this->html->form_text('destination', '', 'Destination', '', 0, 'span12');

$left.=$this->html->form_date('df','','Date from','',0,'span12');
$right.=$this->html->form_date('dt','','Date to','',0,'span12');

$out.=$this->html->cols2($left,$right);
// $out.=$this->html->form_text('year', '', 'Year', '', 0, 'span12');
$out.=$this->html->form_text('limit', '100', 'Limit rows', '', 0, 'span12');
$out.=$this->html->form_hidden('nopager',1);
$out.=$this->html->form_submit('Search');
$out.=$this->html->form_end();
$body.=$out;