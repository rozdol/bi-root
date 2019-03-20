<?php
// echo $this->html->pre_display($_POST,"POST");
// echo $this->html->pre_display($_GET,"GET");
$where=$this->html->readRQ('where');
$filename=$this->html->readRQf('filename');
$emails=$this->html->readRQ('comments');
switch ($where) {
	case 'processed_dir':$path=PROCESSED_DIR;break;
	case 'pdf_dir':$path=PDF_DIR;break;
	case 'scan_dir':$path=SCAN_DIR;break;
	case 'deflated_dir':$path=DEFLATED_DIR;break;
	case 'logs_dir':$path=LOGS_DIR;break;

	case 'NEW_CAMT053':$path=CAMT053_NEW_ROOT_DIR.DS;break;
	case 'NEW_CAMT054':$path=CAMT054_NEW_ROOT_DIR.DS;break;
	case 'NEW_CAMT052':$path=CAMT054_NEW_ROOT_DIR.DS;break;
	case 'NEW_MT940':$path=MT940_NEW_ROOT_DIR.DS;break;
	case 'NEW_MT942':$path=MT942_NEW_ROOT_DIR.DS;break;
	case 'NEW_PAIN002':$path=PAIN002_NEW_ROOT_DIR.DS;break;

	case 'CAMT053':$path=CAMT053_NEW_ROOT_DIR.DS;break;
	case 'CAMT054':$path=CAMT054_NEW_ROOT_DIR.DS;break;
	case 'CAMT052':$path=CAMT054_NEW_ROOT_DIR.DS;break;
	case 'MT940':$path=MT940_NEW_ROOT_DIR.DS;break;
	case 'MT942':$path=MT942_NEW_ROOT_DIR.DS;break;
	case 'PAIN002':$path=PAIN002_NEW_ROOT_DIR.DS;break;

	default:$path=PDF_DIR;
}
if($where=='')$this->html->error('No destination supplied');
if($filename=='')$this->html->error('No filename supplied');
$path=$path.DS.$filename;
if(!file_exists($path))$this->html->error("File <b>$filename</b> not found");

if($emails!=''){
	$emails=str_ireplace(';',',',$emails);
	$emails=str_ireplace("\t",',',$emails);
	$emails=str_ireplace("\n",',',$emails);
	$emails_array=array_map('trim',explode(',',$emails));
	//echo $this->html->pre_display($emails_array,"emails_array");
	foreach ($emails_array as $email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    $valid_emails[]=$email;
		    $status=$this->comm->sendgrid_file('FastConsent:fastconsent@gmail.com', "User:$email", "File:$filename", 'See file attached', [$path]);
		    if($status==1){
		    	echo "$filename is sent to $nmail ($status)<br>";
		    }else{
		    	echo "<b>$filename is failded sent to $nmail ($status)</b><br>";
		    }
		}
	}
	//echo $this->html->pre_display($valid_emails,"valid_emails");
	//$this->comm->sendgrid_file($send_to,'rozdol@gmail.com.com',"$filename",[$path]);

}else{
	$this->utils->dl_file($path, $filename);
}
exit;