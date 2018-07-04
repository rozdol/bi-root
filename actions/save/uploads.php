<?php

$uploaddir=DOCS_DIR;
//echo $this->html->pre_display($_POST,'POST'); ;echo $this->html->pre_display($_GET,'GET'); exit;
//exit;
$update=($this->html->readRQ('update'))*1;
if (!$update) {
    $imgx=0;
    $imgy=0;
    $hasthumb=0;
    $reftype=$this->html->readRQn('reftype');
    $tablename=$this->html->readRQ('tablename');
    $newfname=$this->html->readRQ('newfname');
    if ($newfname!='') {
        $newfname=$this->utils->sanitize_file_name($newfname);
    }
    $refid=$this->html->readRQn('refid');
    $rename=$this->html->readRQn('rename');
    $fromscript=$this->html->readRQn('fromscript');
    $date=$this->dates->F_date("", 1); //$out.= "$refid"; exit;
    if ($tablename=='') {
        $tablename='undefined';
    }
    //$link="&reftype=$reftype&refid=$refid";
    $str = "";
    $hasthumb=0;
    $directory=$uploaddir;
    $newuploadfunc=1;
    if ($newuploadfunc==1) {
        if (($tablename=='documents')&&($refid>0)) {
            $docname=$this->db->GetVal("select name from documents where id=$refid");
            $dirs=explode("-", $docname);
            $y=$dirs[0];
            $m=$dirs[1];
            $d=$dirs[2];
            $newdir=$uploaddir."/$y";
            if (!is_dir($newdir)) {
                mkdir($newdir);
            }
            $newdir=$uploaddir."/$y/$m";
            if (!is_dir($newdir)) {
                mkdir($newdir);
            }
            $newdir=$uploaddir."/$y/$m/$d";
            if (!is_dir($newdir)) {
                mkdir($newdir);
            }

            $docname=str_replace("-", "/", $docname);
            $directory=$uploaddir."/$docname";
        }
    }





    $name='webcam';

    $filename=$_FILES[$name]['name'];
    if ($filename=='') {
        $name='file';
    }
    if ($fromscript>0) {
        $filename=$this->html->readRQ('fromscriptname');
        $filesize=$this->html->readRQ('fromscriptzise');
        $filetype=$this->html->readRQ('fromscripttype');
        $tempname=$this->html->readRQ('fromscripttmp_name');
    } else {
        //$filename=$newfname."-".$_FILES[$name]['name'];
        $filename=$_FILES[$name]['name'];
        $filesize=$_FILES[$name]['size'];
        $filetype=$_FILES[$name]['type'];
        $tempname=$_FILES[$name]['tmp_name'];
    }
    if (($newfname!='')&&($rename>0)) {
        $filename=$newfname;
    }

    echo "This filename:$filename<br>";
    //$orig_name=$filename;
    $name=$filename;
    $filename=$this->utils->normalize_filename($filename);

    $arr1=explode('.', $filename);
    $extention=$arr1[(count($arr1)-1)];

    $fullname=$directory.DS.$filename;



    if (!is_dir($directory)) {
        umask(0);
        mkdir($directory, 0755);
    }
    if (!is_dir($imgdir)) {
        umask(0);
        mkdir($imgdir, 0755);
    }
    if (!is_dir($thmbdir)) {
        umask(0);
        mkdir($thmbdir, 0755);
    }

    $ext = strtolower(strrchr($filename, "."));
    $ext_array=array(".jpg",".png",".tif", ".zip", ".rar", ".pdf", ".dbf", ".xls", ".doc",".pages",".numbers", ".xlsb", ".xlsx", ".docx", ".txt", ".html", ".htm");
    //if (!in_array($ext, $ext_array)) {$this->html->error( "ERROR<br><b>File not aploaded</b> File extention $ext is not allowed for upload." );}
    //Check file name for duplicates
    $i=0;
    while (file_exists($fullname)) {
        $i=$i+1;
        //$newfilename=$uid.'_'.$i.'_'.$filename;
        $newfilename=$i.'_'.$filename;
        $fullname=$directory.DS.$newfilename;
    }
    $out.= "New file :".$fullname."<BR/>";

    $newfilename=basename($fullname);
    $str .=  "File Name :".$filename."<BR/>";
    $str .=  "File TName :".$tempname."<BR/>";
    $str .=  "File Size :".$filesize."<BR/>";
    $str .=  "File Type :".$filetype."<BR/>";
    $str .=  "Full Path :".$fullname."<BR/>";
    $str .= "New File name: ".$newfilename."<BR/>";
    $str .= "File size: ".filesize($fullname)." bytes<BR/>";
    //echo "STR:$str<br>";
    $out.="STR:$str<br>";
    //$random_digit=rand(0000,9999);
    if ($ufile !=none) {
        $out.= "Temp:$tempname, Full:$fullname, Script:$fromscript<br>";
        $out.="<br>.------1<br>";
        if ($fromscript>0) {
            $tmp=copy($tempname, $fullname)*1;
            if ($tmp==0) {
                echo "<div class='red'>Could not copy file  $tempname to $fullname</div>";
                exit;
            }

            //$out.="<br>$tmp=copy($tempname, $fullname)<br>";
            //echo "cp $tempname $fullname<br>";
        } else {
            if (!file_exists($tempname)) {
                $this->html->error("$tempname does not exits.");
            }
            $tmp=move_uploaded_file($tempname, $fullname)*1;
            if ($tmp==0) {
                echo "<div class='red'>Could not move uploaded file  $tempname to $fullname</div>";
                exit;
            }
            //$out.="<br>$tmp=move_uploaded_file($tempname, $fullname)<br>";
        }
        $out.= "TMP:$tmp<br>";

        //echo "OUT:$out<hr>";
        if ($tmp>0) {
            //$out.="<br>------2<br>";
            chmod($fullname, 0600);
            $newfilename=basename($fullname);
            $str .=  "File Name :".$filename."<BR/>";
            $str .=  "File TName :".$tempname."<BR/>";
            $str .=  "File Size :".$filesize."<BR/>";
            $str .=  "File Type :".$filetype."<BR/>";
            if (strlen($filetype)>49) {
                $filetype=substr($filetype, 0, 49);
            }
            $str .=  "Full Path :".$fullname."<BR/>";
            $str .= "New File name: ".$newfilename."<BR/>";
            $str .= "File size: ".filesize($fullname)." bytes<BR/>";

            if (function_exists("mime_content_type")) {
                $str .= "Mime type: ".mime_content_type($fullname)."<br>\n";
            }
            //$out.="<br>------3<br>";
        } else {
            echo $this->html->error("Uploads:<b>File not aploaded!</b><hr>$out");
        }
    }
    $descr=$this->html->readRQ('descr');
    $tags=$this->html->readRQ('tags');
    $sql="INSERT INTO uploads (userid,hasthumb,thumb,descr,tags,name,     refid, reftype,    path,        link,                 filename,        filesize, filetype, tablename,xsize,ysize, date, lastchange) 
	VALUES ($uid,$hasthumb,'$thumb','$descr','$tags','$name', $refid, $reftype, '$fullname', '$newfilename', '$newfilename', '$filesize', '$filetype' ,'$tablename',$imgx, $imgy,'$date', '$date');";
    if ($tablename=='documents') {
        $tmp=$this->db->GetVal("update documents set uploads=uploads+1 where id=$refid");
    }
} else {//update
    $descr=$this->html->readRQ('descr');
    $tags=$this->html->readRQ('tags');
    $sql="UPDATE uploads set descr='$descr', tags='$tags' where id=$id";
}
$cur= $this->db->GetVar($sql);

$str .=$sql;
$out.= "$str";

$logtext.="$name filename=$filename ($name) $nfilename";


$body.=$out;
