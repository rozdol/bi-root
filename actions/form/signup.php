<?php 
$form_opt['well_class']="span11 columns form-wrap";
$form_opt['title']="Registration";

$out.="<hr>";

$out.=$this->html->form_start('signups',0,"",$form_opt);
$out.=$this->html->form_text('name','','Name','John',2,'span12');
$out.=$this->html->form_text('surname','','Surname','Doe',2,'span12');
$out.=$this->html->form_text('email','','e-mail','you@company.com',6,'span12');
$out.=$this->html->form_password('password','','Password','8 chars CAPS & digits',8,'','span12');
$out.=$this->html->form_password('password_confirm','','Re-type password','retype',8,'','span12');
//$out.=$this->html->form_textarea('descr','','Description','Describe your intentions',2,'','span12');
$out.=$this->html->form_submit('Submit');
$out.=$this->html->form_end();

$is_disabled=getenv('REG_DISABLE');
if($is_disabled!='')$this->html->error('Registration of new users temporary unavailble');
$body.=$out;