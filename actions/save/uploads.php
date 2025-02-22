<?php

$uploaddir = DOCS_DIR;
//echo $this->html->pre_display(DOCS_DIR,"data");
//echo $this->html->pre_display($_POST,'POST'); ;echo $this->html->pre_display($_GET,'GET'); exit;
//exit;
$update = ($this->html->readRQn('update')) * 1;
if ($update == 0) {
    $imgx = 0;
    $imgy = 0;
    $hasthumb = 0;
    $reftype = $this->html->readRQn('reftype');
    $tablename = $this->html->readRQ('tablename');
    $newfname = $this->html->readRQ('newfname');
    if ($newfname != '') {
        $newfname = $this->utils->sanitize_file_name($newfname);
    }
    $refid = $this->html->readRQn('refid');
    $rename = $this->html->readRQn('rename');
    $fromscript = $this->html->readRQn('fromscript');
    $date = $this->dates->F_date("", 1); //$out.= "$refid"; exit;
    if ($tablename == '') {
        $tablename = 'undefined';
    }
    //$link="&reftype=$reftype&refid=$refid";
    $str = "";
    $hasthumb = 0;
    $directory = $uploaddir;
    $newuploadfunc = 1;
    if ($newuploadfunc == 1) {
        if (($tablename == 'documents') && ($refid > 0)) {
            $docname = $this->db->GetVal("select name from documents where id=$refid");
            $dirs = explode("-", $docname);
            $y = $dirs[0];
            $m = $dirs[1];
            $d = $dirs[2];
            // $newdir=$uploaddir."/$y";
            // if (!is_dir($newdir)) {
            //     mkdir($newdir,0755, true);
            // }
            // $newdir=$uploaddir."/$y/$m";
            // if (!is_dir($newdir)) {
            //     mkdir($newdir,0755, true);
            // }
            $newdir = $uploaddir . "$y/$m/$d";
            if (getenv('AWS_USE_S3') != 1) {
                if (!is_dir($newdir)) {
                    if (!mkdir($newdir, 0755, true)) {
                        $this->html->error("Failed to make directory: $newdir");
                    }
                }
            }

            $docname = str_replace("-", "/", $docname);
            $directory = $uploaddir . "$docname";
        }
    }
    if (getenv('AWS_USE_S3') == 1) {
        $s3_fullname_parts = explode(DS, $directory);
        array_shift($s3_fullname_parts);
        $directory = implode(DS, $s3_fullname_parts);
        echo "Using AWS S3 for $directory<br>";
    }

    //echo $this->html->pre_display($directory,"directory"); exit;
    $name = 'webcam';

    $filename = $_FILES[$name]['name'];
    if ($filename == '') {
        $name = 'file';
    }
    if ($fromscript > 0) {
        $filename = $this->html->readRQ('fromscriptname');
        $filesize = $this->html->readRQ('fromscriptzise');
        $filetype = $this->html->readRQ('fromscripttype');
        $tempname = $this->html->readRQ('fromscripttmp_name');
    } else {
        //$filename=$newfname."-".$_FILES[$name]['name'];
        $filename = $_FILES[$name]['name'];
        $filesize = $_FILES[$name]['size'];
        $filetype = $_FILES[$name]['type'];
        $tempname = $_FILES[$name]['tmp_name'];
    }
    if (($newfname != '') && ($rename > 0)) {
        $filename = $newfname;
    }

    //echo "This filename:$filename<br>";
    //$orig_name=$filename;
    $name = $filename;
    $filename = $this->utils->normalize_filename($filename);

    $arr1 = explode('.', $filename);
    $extention = $arr1[(count($arr1) - 1)];

    $fullname = $directory . DS . $filename;

    if (!is_dir($directory)) {
        umask(0);
        mkdir($directory, 0755, true);
    }
    if (!is_dir($imgdir)) {
        umask(0);
        mkdir($imgdir, 0755, true);
    }
    if (!is_dir($thmbdir)) {
        umask(0);
        mkdir($thmbdir, 0755, true);
    }

    $ext = strtolower(strrchr($filename, "."));
    $ext_array = array(".jpg", ".png", ".tif", ".zip", ".rar", ".pdf", ".dbf", ".xls", ".doc", ".pages", ".numbers", ".xlsb", ".xlsx", ".docx", ".txt", ".html", ".htm");
    //if (!in_array($ext, $ext_array)) {$this->html->error( "ERROR<br><b>File not aploaded</b> File extention $ext is not allowed for upload." );}
    if (getenv('AWS_USE_S3') == 1) {
        echo "Using AWS S3 for $fullname<br>"; // /data/szc/docs//20/01/0001/vr_user.png
        try {
            $s3 = new Aws\S3\S3Client([
                'region'  => getenv('AWS_REGION'),
                'version' => getenv('S3_VERSION'),
                'credentials' => [
                    'key'    => getenv('AWS_KEY'),
                    'secret' => getenv('AWS_SECRET'),
                ]
            ]);
            echo "(save  uploads) AWS S3 Authenticated<br>";
        } catch (Aws\S3\Exception\S3Exception $e) {
            $message = $e->getMessage();
            $parts = explode('<?xml version="1.0" encoding="UTF-8"?>', $message);
            $error = $parts[2];
            $xml = new SimpleXMLElement($error);
            $this->html->error($xml->Message);
        }
    }



    //Check file name for duplicates
    $i = 0;
    if (getenv('AWS_USE_S3') == 1) {
        while ($s3->doesObjectExist(getenv('AWS_S3_BUCKET'), $fullname)) {
            $i = $i + 1;
            //$newfilename=$uid.'_'.$i.'_'.$filename;
            $newfilename = $i . '_' . $filename;
            $fullname = $directory . DS . $newfilename;
        }
    } else {
        while (file_exists($fullname)) {
            $i = $i + 1;
            //$newfilename=$uid.'_'.$i.'_'.$filename;
            $newfilename = $i . '_' . $filename;
            $fullname = $directory . DS . $newfilename;
        }
    }

    $out .= "New file :" . $fullname . "<BR/>";

    $newfilename = basename($fullname);
    $str .=  "File Name :" . $filename . "<BR/>";
    $str .=  "File TName :" . $tempname . "<BR/>";
    $str .=  "File Size :" . $filesize . "<BR/>";
    $str .=  "File Type :" . $filetype . "<BR/>";
    $str .=  "Full Path :" . $fullname . "<BR/>";
    $str .= "New File name: " . $newfilename . "<BR/>";
    $str .= "File size: " . filesize($fullname) . " bytes<BR/>";
    //echo "STR:$str<br>";
    $out .= "STR:$str<br>";
    //$random_digit=rand(0000,9999);
    if (1==1) {
        $out .= "Temp:$tempname, Full:$fullname, Script:$fromscript<br>";
        $out .= "<br>.------1<br>";
        if ($fromscript > 0) {

            if (getenv('AWS_USE_S3') == 1) {
                try {
                    $result = $s3->putObject([
                        'Bucket' => getenv('AWS_S3_BUCKET'),
                        'Key'    => $fullname,
                        'SourceFile' => $tempname
                    ]);
                    echo "AWS S3 File uploaded<br>";
                    if (!unlink($tempname)) $this->html->error("Could not remove tmp file  $tempname");
                } catch (Aws\S3\Exception\S3Exception $e) {
                    $message = $e->getMessage();
                    $parts = explode('<?xml version="1.0" encoding="UTF-8"?>', $message);
                    $error = $parts[2];
                    $xml = new SimpleXMLElement($error);
                    $this->html->error($xml->Message);
                }
            } else {
                $tmp = copy($tempname, $fullname) * 1;
                if ($tmp == 0) {
                    if ($GLOBALS['access']['main_admin']) {
                        echo "debug::$out<br>";
                    }
                    echo "<div class='red'>Could not copy file  $tempname to $fullname</div>";
                    exit;
                }
            }

            //$out.="<br>$tmp=copy($tempname, $fullname)<br>";
            //echo "cp $tempname $fullname<br>";
        } else {
            if (!file_exists($tempname)) {
                $this->html->error("$tempname does not exits.");
            }
            if (getenv('AWS_USE_S3') == 1) {
                try {
                    $result = $s3->putObject([
                        'Bucket' => getenv('AWS_S3_BUCKET'),
                        'Key'    => $fullname,
                        'SourceFile' => $tempname
                    ]);
                    echo "AWS S3 File uploaded<br>";
                    if (!unlink($tempname)) $this->html->error("Could not remove tmp file  $tempname");
                } catch (Aws\S3\Exception\S3Exception $e) {
                    $message = $e->getMessage();
                    $parts = explode('<?xml version="1.0" encoding="UTF-8"?>', $message);
                    $error = $parts[2];
                    $xml = new SimpleXMLElement($error);
                    $this->html->error($xml->Message);
                }
            } else {
                $tmp = move_uploaded_file($tempname, $fullname) * 1;
                if ($tmp == 0) {
                    $this->html->error("Could not move uploaded file  $tempname to $fullname");
                }
            }
        }
    }
    $descr = $this->html->readRQ('descr');
    $tags = $this->html->readRQ('tags');
    $sql = "INSERT INTO uploads (userid,hasthumb,thumb,descr,tags,name,     refid, reftype,    path,        link,                 filename,        filesize, filetype, tablename,xsize,ysize, date, lastchange) 
	VALUES ($uid,$hasthumb,'$thumb','$descr','$tags','$name', $refid, $reftype, '$fullname', '$newfilename', '$newfilename', '$filesize', '$filetype' ,'$tablename',$imgx, $imgy,'$date', '$date');";
    if ($tablename == 'documents') {
        $tmp = $this->db->GetVal("update documents set uploads=uploads+1 where id=$refid");
    }
} else { //update
    $descr = $this->html->readRQ('descr');
    $tags = $this->html->readRQ('tags');
    $sql = "UPDATE uploads set descr='$descr', tags='$tags' where id=$id";
}
$cur = $this->db->GetVar($sql);

$str .= $sql;
$out .= "$str";

$logtext .= "$name filename=$filename ($name) $nfilename";


$body .= $out;
//echo $body; exit;
