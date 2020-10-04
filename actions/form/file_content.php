<?php
$form_opt['well_class']="span11 columns form-wrap";

$out.=$this->html->form_start($what, $id, '', $form_opt);
$out.="<hr>";
$key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));

$out.=$this->html->form_hidden('filename', $filename);
$out.=$this->html->form_hidden('where', $where);

$out.=$this->html->form_textarea('content', 'Bla', 'Key', '', 0, '', 'span12');

$out.=$this->html->form_submit('Save');
$out.=$this->html->form_end();

$body.=$out;