<?php
if ($what == 'uploads') {
    $uploaddir=DATA_DIR.'docs';
    if ($this->data->isallowed('uploads', $id, $GLOBALS['project'])==0) {
        $this->data->DB_log("HACK on $what id=$id");
        $this->html->error('You have no access to this file.');
    }
        $sql = "SELECT * FROM uploads WHERE id = '$id'";
        $res=$this->utils->escape($this->db->GetRow($sql));
    if ($res[active]=='f') {
        $uploaddir=DATA_DIR.'docs/deleted';
    }
        $res[filename]=str_ireplace("'", "\'", $res[filename]);
        //if(substr($res[filename],0,7)=='DELETED')$uploaddir="/data/deleted";
        $filename=$uploaddir."/".$res[filename];
        $newuploadfunc=1;
    if ($newuploadfunc==1) {
        if ($res[tablename]=='documents') {
            $docname=$this->db->GetVal("select name from documents where id=$res[refid]");
            $docname=str_replace("-", "/", $docname);
            $filename=$uploaddir."/$docname/".$res[filename];
        }
    }
    //echo $this->html->pre_display($filename,"$res[name]");
        //$docname=$this->db->GetVal("select name from documents where id=$res[refid]");
        //$filename=$uploaddir."/$docname/".$res[filename];

        if(getenv('AWS_USE_S3')==1){
            $bas_filename = basename($filename);
            $file_extension = strtolower(substr(strrchr($bas_filename, "."), 1));

            $ctype=$this->utils->content_type($filename);
            if(strlen($file_extension)>10)$ctype="text/plain";

            //echo "ctype:$ctype<br>"; exit;
            $s3_fullname_parts=explode(DS,$filename);
            array_shift($s3_fullname_parts);
            $s3_fullname=implode(DS,$s3_fullname_parts);
            $s3_fullname=str_ireplace('private/','',$s3_fullname);
            //echo "Using AWS S3 for $s3_fullname<br>";
            try{
                $s3 = new Aws\S3\S3Client([
                    'region'  => getenv('AWS_REGION'),
                    'version' => getenv('S3_VERSION'),
                    'credentials' => [
                        'key'    => getenv('AWS_KEY'),
                        'secret' => getenv('AWS_SECRET'),
                    ]
                ]);
                //echo "AWS S3 Authenticated<br>";
            } catch (Aws\S3\Exception\S3Exception $e) {
                $message=$e->getMessage();
                $parts=explode('<?xml version="1.0" encoding="UTF-8"?>',$message);
                $error=$parts[2];
                $xml = new SimpleXMLElement($error);
                $this->html->error($xml->Message);
            }

            try {
                // Get the object.
                $result = $s3->getObject([
                    'Bucket' => getenv('AWS_S3_BUCKET'),
                    'Key'    => $s3_fullname
                ]);

                // Display the object in the browser.
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer $filename");
                header("Content-Type: $ctype");
                $header="Content-Disposition: inline; filename=\"$res[name]\";";
                header($header);
                header("Content-Transfer-Encoding: binary");
                //header("Content-Length: ".$len);
                header("Content-Length: {$result['ContentLength']}");
                //header("Content-Type: {$result['ContentType']}");
                echo $result['Body'];
                //echo $this->html->pre_display($result,"result");
            } catch (Aws\S3\Exception\S3Exception $e) {
                $message=$e->getMessage();
                $parts=explode('<?xml version="1.0" encoding="UTF-8"?>',$message);
                $error=$parts[2];
                $xml = new SimpleXMLElement($error);
                $this->html->error($xml->Message." KEY:$s3_fullname");
            }

            exit;
        }else{
            $this->utils->dl_file($filename, $res[name]);
        }


        /*
        if (file_exists($filename))$this->utils->dl_file($filename,$res[name]);else {if($access[main_admin])$not_f=$filename; $out.= "<h1>ERROR 404</h1><h3>File not found!</h3>$filename";}
        if (file_exists($filename)) {
            //$this->utils->dl_file($filename,$res[name]);
        } else {
                $iy="и"."̆";
                $res[filename]=str_ireplace("й",$iy,$res[filename]);
                $iy="е"."̈";
                $res[filename]=str_ireplace("ё",$iy,$res[filename]);
                $filename=$uploaddir."/".$res[filename];
                //$this->utils->dl_file($filename,$res[name]);
        }
        */
}
    
$body.=$out;
