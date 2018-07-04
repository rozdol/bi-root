<?php
//save genform

//$ find /www/bi6/consumers/is/procedures -type d -exec chmod 777 {} \;
//chown -R admin:staff /www/bi6/consumers/is/procedures	
	
$table_name=$this->html->readRQ('tablename');
//form
$text=$_POST[content_form];
$text=str_replace('t extarea','textarea',$text);
$proc_file=APP_DIR.'/procedures/form/'.$table_name.'.php';

if(!file_exists($proc_file)){
	if(!file_put_contents($proc_file, $text))echo "ERR $proc_file<br>";;
};
//show
$text=$_POST[content_show];
$proc_file=APP_DIR.'/procedures/show/'.$table_name.'.php';
if(!file_exists($proc_file)){
	if(!file_put_contents($proc_file, $text))echo "ERR $proc_file<br>";;
};

//save
$text=$_POST[content_save];
$proc_file=APP_DIR.'/procedures/save/'.$table_name.'.php';
if(!file_exists($proc_file)){
	if(!file_put_contents($proc_file, $text))echo "ERR $proc_file<br>";;
};


//details
$text=$_POST[content_details];
$proc_file=APP_DIR.'/procedures/details/'.$table_name.'.php';
if(!file_exists($proc_file)){
	if(!file_put_contents($proc_file, $text))echo "ERR $proc_file<br>";;
};

exit;
//echo "<textarea>$text</textarea>";
//echo $this->html->pre_display($_POST,'Post'); echo $this->html->pre_display($vals,'Vals');exit;