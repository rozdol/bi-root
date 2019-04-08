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



$allowed_dirs=[
	FILES_DIR,

	NEW_FILES_DIR,
	PROCESSED_FILES_DIR,
	PDF_FILES_DIR,

	CAMT053_DIR,
	CAMT054_DIR,
	MT940_DIR,
	MT942_DIR,
	PAIN002_DIR,

	CAMT053_NEW_ROOT_DIR,
	CAMT054_NEW_ROOT_DIR,
	MT940_NEW_ROOT_DIR,
	MT942_NEW_ROOT_DIR,
	PAIN002_NEW_ROOT_DIR,

	CAMT053_PROCESSED_ROOT_DIR,
	CAMT054_PROCESSED_ROOT_DIR,
	MT940_PROCESSED_ROOT_DIR,
	MT942_PROCESSED_ROOT_DIR,
	PAIN002_PROCESSED_ROOT_DIR,

	SCAN_DIR,
	PDF_DIR,
	PROCESSED_DIR,
	DEFLATED_DIR,
	LOGS_DIR

];
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