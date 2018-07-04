<?php
//?act=tools&what=migrate_from_old&type=table&object=products
//?act=tools&what=migrate_from_old&type=append&object=getpartnerid
//?act=tools&what=migrate_from_old&type=function&object=gen_bls
if ($GLOBALS[access][main_admin]) {
    $table='formulas';
    $type=$this->html->readRQ('type');
    if ($type=='') {
        $type='table';
    }
    $object=$this->html->readRQ('object');
    if ($object=='') {
        $object='formulas';
    }

    $suffix="_main";
    if ($type=='function') {
        $openf="function ";
        $closef="(";
        $closef2="function ";
        $begin="function ";
        $actions_set=array('functions');
    } else {
        $openf="what == '";
        $closef="'){";
        $closef2="//---------";
        $begin="if (\$what == '";
        $actions_set=array('show','form','save','details','search');
    }
    if ($type=='append') {
        $openf="if (\$what == \"";
        $closef="\") {";
        $closef2="//---------";
        $begin="if (\$what == \"";

        $actions_set=array('append');
        $suffix='';
        define(OLD_APP_DIR, '/private/var/www/suek/core/modules/core/');
    } else {
        define(OLD_APP_DIR, '/private/var/www/suek/cc/modules/main/');
    }


//$actions_set=array('show','form','save','details','search');
//$actions_set=array('show');
//echo $this->html->pre_display($actions_set,"actions_set");
    foreach ($actions_set as $action) {
        $source_file=OLD_APP_DIR.$action."$suffix.php";

        if (!file_exists($source_file)) {
            $this->html->error("no file $source_file");
        }
        $files[$action]=$source_file;
    }
    echo $this->html->pre_display($files, "files");


    foreach ($files as $key => $value) {
    //echo "$value<br>";
        $file_c = file_get_contents($value) or die("Unable to open file! $value");
        ;
        $file_c=$this->utils->clean_old_code($file_c);
    //var_dump($file_c);
    //echo $this->html->pre_display($file_c);

//  $myfile = fopen("/data/szc/wiplist.txt", "r") or die("Unable to open file!");
// echo fread($myfile,filesize("webdictionary.txt"));
// fclose($myfile);

    //
    //echo $this->html->pre_display(htmlspecialchars($file_c,'ENT_COMPAT | ENT_XHTML','ISO-8859-1'),$key);
        $func=explode($openf, $file_c);
    //echo $this->html->pre_display(htmlspecialchars($func,'ENT_COMPAT | ENT_XHTML','ISO-8859-1'));
    //echo $this->html->pre_display($func);
        foreach ($func as $function) {
            $fname=explode($closef, $function);
            if (($fname[0]!='')&&($i>0)) {
                $parts=explode("'", $fname[0]);
                $filename=$parts[0];
                echo "Function($i): ($filename)<br>";
                if ($filename==$object) {
                    echo "Function($i): ($filename) <a href='?act=$key&what=$filename'>$key $filename</a><br>";
                    
            //$chunk=explode($closef2,$function);
                    
                    $chunks=explode($closef2, $function);
            //$chunks=explode($openf,$function);
                    $text=$chunks[0];
                    $text = rtrim(trim(str_replace("$filename$closef", '', $text)), "}");
                    if ($type=='function') {
                        $chunks=explode("\n", $text);
                        $func_vars=trim($chunks[0]);
                //$chunks=array_shift($chunks);
                        unset($chunks[0]);
                        $text=implode("\n", $chunks);
                        $f_header="function $filename($func_vars\$f=__FUNCTION__;return include(FW_DIR.'/helpers/f.php');}";

                        $text="//params ($func_vars\n\n$text";
                    }
                    $text="<?php\n".$text;

                    
                    

                    if ((!$this->utils->contains('$body.=', $text))&&($type!='function')) {
                        $text="$text\n\$body.=\$out;\n";
                    }
                    
            //file_tmp=$destination_dir."/--$filename.php";
                    $file_tmp=$destination_dir."/$filename.php";
            //$file=$destination_dir."/$filename.php";
                    if ((!file_exists($file))&&(!file_exists($file_tmp))) {
                        $dest_file=APP_DIR."actions".DS.$key.DS.$filename.".php";
                        if ($type=='function') {
                            $dest_file=APP_DIR.$key.DS.$filename.".php";
                        }
                        echo "Save to $dest_file<br>";
                        echo $this->html->pre_display($text, "$filename $key");
                //echo $this->html->pre_display($text);
                        if (!file_exists($dest_file)) {
                            if (!file_put_contents($dest_file, $text)) {
                                echo "<span class='label red'>Can not save to $dest_file</span><br>";
                            }
                        }
                        if ($type=='function') {
                            $project_file=APP_DIR."classes".DS."project.php";
                            $file_c = file_get_contents($project_file) or die("Unable to open file! $project_file");
                            $file_c=str_replace("//replace_placeholder", "$f_header\n\t//replace_placeholder", $file_c);
                            if (!file_exists($project_file)) {
                                file_put_contents($project_file, $file_c) or die("Unable to save file! $project_file");
                            }
                        }
                    }
                    
                    $files[$i]=$filename;
                }
            }
            $i++;
        }
    }
}
