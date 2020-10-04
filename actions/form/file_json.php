<?php
$filename=$this->html->readRQf('filename');
$where=$this->html->readRQ('where');
$name=$filename;
$file_extension=$this->utils->file_extension($filename);
if($file_extension!='json')$this->html->error('Not JSON');
switch ($where) {
	case 'processed_dir':$path=PROCESSED_DIR;break;
	case 'pdf_dir':$path=PDF_DIR;break;
	case 'scan_dir':$path=SCAN_DIR;break;
	case 'deflated_dir':$path=DEFLATED_DIR;break;
	case 'logs_dir':$path=LOGS_DIR;break;
	case 'LANG':$path=DATA_DIR.'/lang/';break;
	default:$path=PDF_DIR;
}
if($filename=='')$this->html->error('No filename supplied');
$basename=basename($filename);
if($filename==$basename)$filename=$path.DS.$basename;
$path=$filename;
if(!file_exists($path))$this->html->error("File <b>$basename</b> not found");

//$out.=$this->html->tag($name,'h1','');

//$out.=$this->html->tag("$filename",'foldered','class');
//$partner=$this->project->get_partner_info($id,$date);
$array=json_decode(file_get_contents($path),true);
//echo $this->html->pre_display($array,"array");
//$json=json_decode($this->data->get_val('partners','json',$id),true);
$form=$this->html->array_values_form($array,$basename, $where);
$out.=$form[out];

//$out.=$this->html->pre_display($form[js],"js");
$js="
<script>
$(document).ready(function() {
     $form[js]
 });
</script>";
$out.=$js;
$body.=$out;