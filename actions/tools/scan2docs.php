<?php
if ($access['edit_uploads']) {
    $id=$this->html->readRQ('id')*1;
    //$backupdir=$db->GetVal("select value from config where name='scandir'");
    $backupdir=$GLOBALS['scandir'];
    $cmd_ls=$GLOBALS['cmd_ls'];
    $exec="$cmd_ls $backupdir ";
    
    //$backupdir="/data/szc/lh";
    $all_files = scandir($backupdir);
    foreach ($all_files as $file) {
        $file_l=strtolower($file);
        $pos = strpos($file_l, ".pdf")*1;
        if (($pos > 0 )&&($file_l!='')) {
            //echo "PDF File:$file<br>";
            $file_orig=$file;
            $file=str_replace("#", "-", $file);
            rename("$backupdir/$file_orig", "$backupdir/$file");
            
            $file_name=substr($file, 0, 10);
            $file_name=str_replace("#", "-", $file_name);
            $docid=$this->db->GetVal("select id from documents where name='$file_name'")*1;
            $docs=$this->db->GetVal("select count(*) from uploads where refid='$docid' and tablename='documents'")*1;
            $string ="PDF File:$file, ($file_name) [DOC_ID:$docid] Uploads:$docs<br>";
            echo "$string";
            if ($docid>0) {
                echo "DOCID: $docid<br>";
                $row=$this->db->GetRow("select * from documents where id=$docid");
                $fdate=$this->dates->F_YMDate($row[datefrom]);
                $ftype=$this->db->GetVal("select name from listitems where id=$row[type]");
                $partnersnamelist=$this->utils->F_tostring($this->db->GetResults("select substr(p.name,0,7) as name from partners p, docs2partners d where d.docid=$docid and d.partnerid=p.id"));
                $partnersnamelist=substr($partnersnamelist, 0, strlen($partnersnamelist)-1);
                $fsum=$row[amount];
                $ftype=str_replace("/", "or", $ftype);
                $newfname="$fdate-$ftype-$partnersnamelist-$fsum";

                $_POST[reftype]=0;
                $_POST[tablename]='documents';
                $_POST[newfname]="$newfname";
                $_POST[refid]=$docid;
                $_POST[fromscript]=1;

                $_POST[fromscriptname]=$file;
                $_POST[fromscriptzise]=filesize("$backupdir/$file");
                $_POST[fromscripttype]='application/pdf';
                $_POST[fromscripttmp_name]="$backupdir/$file";
                $logtext.="$file,";
                echo "Trying to save file: $file<br>";
                $this->save('uploads');
                echo "File Saved<br>";
                $scans++;
                $unlink=unlink("$backupdir/$file");
                //echo "<br>unlink=$unlink unlink(\"$backupdir/$file\");";
            }
            echo "<hr>";
        }
    }
    //echo "<pre>$backupdir\n$file1</pre>";
    //print_r($files1);
    
    //echo "$scans are uploaded";
    $body.=$out;
    echo $body;
    exit;
}
