<?php
if ($what == 'uploads') {
    $uploaddir=DATA_DIR.'/docs';
    if ($this->data->isallowed('uploads', $id, $GLOBALS['project'])==0) {
        echo "You have no access to this file.";
        $this->data->DB_log("HACK on $what id=$id");
        exit;
    }
        $sql = "SELECT * FROM uploads WHERE id = '$id'";
        $res=$this->utils->escape($this->db->GetRow($sql));
    if ($res[active]=='f') {
        $uploaddir=DATA_DIR.'/docs/deleted';
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
        //$docname=$this->db->GetVal("select name from documents where id=$res[refid]");
        //$filename=$uploaddir."/$docname/".$res[filename];
        $this->utils->dl_file($filename, $res[name]);
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
