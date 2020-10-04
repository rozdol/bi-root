<?php
//echo $this->html->pre_display($_POST,"_POST");
$file_name=$this->html->readRQf('file_name');
unset($_POST[file_name]);
$basename=basename($file_name);
$file_extension=$this->utils->file_extension($file_name);
if($file_extension!='json')$this->html->error('Not JSON');
$where=$this->html->readRQs('where');
unset($_POST[where]);

foreach ($_POST as $key => $value) {
	$value2=$this->html->readRQs($key);
	//echo "$key=$value2<br>";
	$key=str_ireplace('_',' ',$key);
	$arr[$key]=$value2;

}
$lang_file_content =html_entity_decode(json_encode($arr,JSON_UNESCAPED_UNICODE), null, 'UTF-8');
$lang_file_content=str_ireplace(',"',",\n\"",$lang_file_content);
$lang_file_content=str_ireplace('{',"{\n",$lang_file_content);
$lang_file_content=str_ireplace('}',"\n}\n",$lang_file_content);
switch ($where) {
	case 'processed_dir':$path=PROCESSED_DIR;break;
	case 'pdf_dir':$path=PDF_DIR;break;
	case 'scan_dir':$path=SCAN_DIR;break;
	case 'deflated_dir':$path=DEFLATED_DIR;break;
	case 'logs_dir':$path=LOGS_DIR;break;
	case 'LANG':$path=DATA_DIR.'/lang';break;
	default:$path=PDF_DIR;
}

$lang_file = $path.DS.$basename;
//echo "$lang_file<br>";
// if (!file_exists($lang_file)) {
//     $lang_file =DATA_DIR.'/lang/' . 'en-us.json';
// }
if(!file_put_contents($lang_file, $lang_file_content)){echo "lang_file:$lang_file<br>";echo "lang_file_content:$lang_file_content<br>";};
//echo $this->html->pre_display($lang_file_content,"lang_file_content");
echo $this->html->message("File $basename is saved");
exit;