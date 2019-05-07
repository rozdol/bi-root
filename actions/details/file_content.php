<?php
// echo $this->html->pre_display($_POST,"POST");
// echo $this->html->pre_display($_GET,"GET");
$where=$this->html->readRQ('where');
$filename=$this->html->readRQp('filename');
//$this->html->error($filename);

$emails=$this->html->readRQ('comments');
switch ($where) {
	case 'processed_dir':$path=PROCESSED_DIR;break;
	case 'pdf_dir':$path=PDF_DIR;break;
	case 'scan_dir':$path=SCAN_DIR;break;
	case 'deflated_dir':$path=DEFLATED_DIR;break;
	case 'logs_dir':$path=LOGS_DIR;break;

	case 'NEW_CAMT053':$path=CAMT053_NEW_ROOT_DIR.DS;break;
	case 'NEW_CAMT052':$path=CAMT052_NEW_ROOT_DIR.DS;break;
	case 'NEW_MT940':$path=MT940_NEW_ROOT_DIR.DS;break;
	case 'NEW_MT942':$path=MT942_NEW_ROOT_DIR.DS;break;
	case 'NEW_PAIN002':$path=PAIN002_NEW_ROOT_DIR.DS;break;

	case 'CAMT053':$path=CAMT053_NEW_ROOT_DIR.DS;break;
	case 'CAMT052':$path=CAMT052_NEW_ROOT_DIR.DS;break;
	case 'MT940':$path=MT940_NEW_ROOT_DIR.DS;break;
	case 'MT942':$path=MT942_NEW_ROOT_DIR.DS;break;
	case 'PAIN002':$path=PAIN002_NEW_ROOT_DIR.DS;break;

	default:$path=PDF_DIR;
}



$allowed_dirs=[
	FILES_DIR,

	NEW_FILES_DIR,
	PROCESSED_FILES_DIR,
	FIALED_FILES_DIR,
	PDF_FILES_DIR,

	CAMT053_DIR,
	CAMT052_DIR,
	MT940_DIR,
	MT942_DIR,
	PAIN002_DIR,

	CAMT053_NEW_ROOT_DIR,
	CAMT052_NEW_ROOT_DIR,
	MT940_NEW_ROOT_DIR,
	MT942_NEW_ROOT_DIR,
	PAIN002_NEW_ROOT_DIR,

	CAMT053_PROCESSED_ROOT_DIR,
	CAMT052_PROCESSED_ROOT_DIR,
	MT940_PROCESSED_ROOT_DIR,
	MT942_PROCESSED_ROOT_DIR,
	PAIN002_PROCESSED_ROOT_DIR,

	CAMT053_FIALED_ROOT_DIR,
	CAMT052_FIALED_ROOT_DIR,
	MT940_FIALED_ROOT_DIR,
	MT942_FIALED_ROOT_DIR,
	PAIN002_FIALED_ROOT_DIR,

	SCAN_DIR,
	PDF_DIR,
	PROCESSED_DIR,
	DEFLATED_DIR,
	LOGS_DIR

];
$key=getenv('SENDGRID_API_KEY');
$use_sendmail=($key=='')?1:0;
//echo "Use Sendmail: $use_sendmail<br>"; exit;
//if($where=='')$this->html->error('No destination supplied');
if($filename=='')$this->html->error('No filename supplied');
$basename=basename($filename);
$allowed=0;
foreach ($allowed_dirs as $allowed_dir) {
	if($this->utils->contains($allowed_dir, $filename)) $allowed=1;
}
if($allowed==0)$this->html->error("No access to file $basename");
$path=$filename;
if(!file_exists($path))$this->html->error("File <b>$basename</b> not found");
$body='See file attached';
$subject="File:$basename";
$files[]=$path;
if($emails!=''){
	$emails=str_ireplace(';',',',$emails);
	$emails=str_ireplace("\t",',',$emails);
	$emails=str_ireplace("\n",',',$emails);
	$emails_array=array_map('trim',explode(',',$emails));
	//echo $this->html->pre_display($emails_array,"emails_array");
	foreach ($emails_array as $email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    $valid_emails[]=$email;
		    if($use_sendmail){
		    	//$status=$this->comm->send_attachment_mail('BI:'.$GLOBALS['settings']['system_email'], "User:$email", "File:$basename", 'See file attached', [$path]);
		    	echo "Sending to:$email<br>";
		    	//$status=$this->comm->send_attachment_mail($GLOBALS['settings']['system_email'], $email, $subject, $body, [$path]);
		    	$status=$this->comm->send_attachment_mail($GLOBALS['settings']['system_email'], $email, $subject, $body, $files);
		    	//echo $this->html->pre_display($status,"status");
		    	//if($status->ErrorInfo())
		    }else{
		    	$status=$this->comm->sendgrid_file('BI:'.$GLOBALS['settings']['system_email'], "User:$email", $subject, $body, [$path]);
		    	if($status==1){
		    		echo "sendgrid_file: $basename is sent to $nmail ($status)<br>";
		    	}else{
		    		echo "<b>$basename is failded sent to $nmail ($status)</b><br>";
		    	}
		    }



		}
	}
	//echo $this->html->pre_display($valid_emails,"valid_emails");
	//$this->comm->sendgrid_file($send_to,$GLOBALS['settings']['system_email'],"$filename",[$path]);

}else{
	$this->utils->dl_file($path, $filename);
}
exit;