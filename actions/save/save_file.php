<?php
$name='file';
//echo $this->html->pre_display($_FILES,'FILES');
//echo $this->html->pre_display($_GET,'GET');
//echo $this->html->pre_display($_POST,'POST');
$allowed=json_decode($this->html->readRQ('allowed'), true);
$process=$this->html->readRQn('process');
$run_function=$this->html->readRQ('run_function');
$redirect_url=$this->html->readRQ('redirect_url');
$destination=$this->html->readRQ('destination');
//echo $this->html->pre_display($allowed,'allowed');
$filename=$_FILES[$name]['name'];
$filesize=$_FILES[$name]['size'];
$filetype=$_FILES[$name]['type'];
$tmp_name=$_FILES[$name]['tmp_name'];
$filename=$this->utils->normalize_filename($filename);
$ext = strtolower(strrchr($filename, "."));
$ext_array=array(".jpg",".png",".tif", ".zip", ".rar", ".pdf", ".dbf", ".xls", ".doc",".pages",".numbers", ".xlsb", ".xlsx", ".docx", ".txt", ".html", ".htm");
if (!in_array($ext, $ext_array)) {
    $this->html->error("ERROR<br><b>File not aploaded</b> File extention $ext is not allowed for upload.");
}
if (!in_array($ext, $allowed)) {
    $this->html->error("ERROR<br><b>File not aploaded</b> File extention $ext is not allowed for upload.");
}

$path=APP_DIR.'unprotected'.DS;


if ($destination=='data') {
    $path=DATA_DIR.DS.'data'.DS;
}
if ($destination=='tmp') {
    $path=DATA_DIR.DS.'tmp'.DS;
}
if ($destination=='signs') {
    $path=DATA_DIR.DS.'signs'.DS;
}
if ($destination=='lh') {
    $path=DATA_DIR.DS.'lh'.DS;
}
if ($destination=='stamps') {
    $path=DATA_DIR.DS.'stamps'.DS;
}

$dest_file=$path.$filename;
echo "$dest_file";
if (!(move_uploaded_file($tmp_name, $dest_file))) {
    echo $this->html->error("File $filename not uploaded. ".$tmp_name.' -> '.$dest_file);
}
//


if (($redirect_url!=='')&&($process==0)) {
    $redirect_url="$redirect_url&run_function=$run_function&filename=$filename&destination=$destination";
    //echo "redirect_url=$redirect_url<br>";// exit;
    echo $this->html->refreshpage($redirect_url, 0.1, 'Processing...');
    exit;
}

//if(!(move_uploaded_file($tmp_name, $tmp_name.$ext)))echo $this->html->error("File $place not uploaded. ".$tmp_name.' -> '.$path.$filename);
//$process=0;
if (($run_function=='run')&&($process>0)) {
    $this->project->run($dest_file);
}

if (($run_function=='rcb_import')&&($process>0)) {
    $this->project->rcb_import($dest_file);
}

if (($run_function=='rcb_compare')&&($process>0)) {
    $this->project->rcb_compare($dest_file);
}


if ($process>0) {
    if (!(unlink($dest_file))) {
        echo $this->html->error("File $dest_file can not be removed. ".$tmp_name.' -> '.$dest_file);
    };
}
//exit;
