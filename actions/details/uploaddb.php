<?php
if ($what == 'uploaddb') {
        $file=$this->html->readRQ('file');
        $file=str_ireplace("/", "", $file);
        $file=str_ireplace("\\", "", $file);
        $file=str_ireplace("..", "", $file);
        $filename=$backupdir."/".$file;
        $this->utils->dl_file($filename, $file);
}
    
$body.=$out;
