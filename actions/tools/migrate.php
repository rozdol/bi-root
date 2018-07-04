<?php
//--------------------------
if (($what == 'migrate')&&($access['main_admin'])) {
    $the_action='functions';
    $mod='acc';
    if ($the_action=='report') {
        $suffix="s";
    }
    //define(OLD_APP_DIR, '/Library/PostgreSQL/EnterpriseDB-ApachePhp/apache/www/bi/consumers/szc');
    //define(OLD_FW_DIR, '/Library/PostgreSQL/EnterpriseDB-ApachePhp/apache/www/bi/bi-framework');
    
    define(OLD_FW_DIR, '/www/bi/bi-framework');
    define(OLD_APP_DIR, '/www/bi/consumers/szc');
    
    //Replace globally "//==" with "//----------" to get more accurate export results;
    
    //framework
    //$source_file="/www/bi/bi-framework/modules/core/$the_action.php";
    //$destination_dir="/www/bi6/framework/procedures/$the_action";
    
    //$source_file="/Library/PostgreSQL/EnterpriseDB-ApachePhp/apache/www/bi/bi-framework/modules/core/".$the_action.$suffix.".php";
    //$source_file="/Library/PostgreSQL/EnterpriseDB-ApachePhp/apache/www/bi/bi-framework/modules/$mod/".$the_action.$suffix."_$mod.php";
    //$destination_dir=FW_DIR."/procedures/$the_action";
    
    //$source_file=OLD_FW_DIR."/modules/core/$the_action".".php";
    //$source_file=OLD_FW_DIR."/modules/core/compare".".php";
    //$source_file=OLD_FW_DIR."/modules/$mod/".$the_action.$suffix."_$mod.php";
    //$destination_dir=FW_DIR."/procedures/$the_action";
    //$destination_dir=FW_DIR."/$the_action";
    
    
    //consumers
    $source_file=OLD_APP_DIR."/modules/main/$the_action".$suffix."_main.php";
    //$destination_dir=APP_DIR."/procedures/$the_action";
    $destination_dir=APP_DIR."/$the_action";
    
    
    //$source_file=OLD_APP_DIR."/modules/main/$the_action"."_main.php";
    //$destination_dir=APP_DIR."/procedures/$the_action";
    //$destination_dir=APP_DIR."/$the_action";
    
    if (!file_exists($source_file)) {
        $this->html->error("no file $source_file");
    }

    if ($the_action=='functions') {
        $openf="function ";
        $closef="(";
        $closef2="function ";
        $begin="function ";
    } else {
        $openf="what == '";
        $closef="){";
        $closef2="//---------";
        $begin="if (\$what == '";
    }

    $file_c = file_get_contents($source_file);
    echo "<hr>From $source_file:<br>";
    $i=0;
    $file_c = str_replace('$out.= "<pre>";print_r($_POST);$out.= "</pre>";$out.= "<pre>";print_r($vals);$out.= "</pre>";exit;', '', $file_c);
    $file_c = str_replace('$out.= $out;', '', $file_c);
    $file_c = str_replace('$out.=$out;', '', $file_c);
    $file_c = str_replace('$body.= "$out";', '', $file_c);
    $file_c = str_replace('$out.=$out;', '', $file_c);
    $file_c = str_replace('$fav=isinfavorites($what,$id);', '$out.=$this->data->details_bar($what,$id);', $file_c);
    $file_c = str_replace('$out.= "<div class=\'alert alert-info\'>$fav :: <a href=\'?act=edit&table=$what&id=$id\'><img src=\'".APP_URI."/assets/img/custom/edit.png\'> Edit </a> :: <a href=\'?act=edit&table=notify&refid=$id&tablename=$what\'><img src=\'".APP_URI."/assets/img/custom/MailSend.png\'> Notify </a>$isnotified</div>";', '', $file_c);
    
    
    
    $file_c = str_replace(
        'if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}',
        'if (!($cur = pg_query($sql))) {$out.=$this->html->pre_display($sql."\n".pg_last_error(),\'SQL error\',\'red\');}
	$rows=pg_num_rows($cur);
	$csv.=$this->data->csv($sql);',
        $file_c
    );
    
    
    
    $file_c = str_replace("echo", "\$out.=", $file_c);
    $file_c = str_replace("print ", "\$out.=", $file_c);
    $file_c = str_replace("\$out.= \"\$out", "\$body.= \"\$out", $file_c);
    $file_c = str_replace('readRQ', '$this->utils->readRQ', $file_c);
    
    $file_c = str_replace('$db->', '$this->db->', $file_c);
    $file_c = str_replace('insert_db(', '$this->db->insert_db(', $file_c);
    $file_c = str_replace('update_db(', '$this->db->update_db(', $file_c);
    
    $file_c = str_replace('HT_ajaxpager', '$this->html->HT_ajaxpager', $file_c);
    $file_c = str_replace('HT_pager', '$this->html->HT_pager', $file_c);
    $file_c = str_replace('HT_editicons', '$this->html->HT_editicons', $file_c);
    $file_c = str_replace('HT_Error', '$this->html->error', $file_c);
    
    //$file_c = str_replace('F_toarray', '$this->utils->F_toarray',$file_c);
    //$file_c = str_replace('F_tostring', '$this->utils->F_tostring',$file_c);
    //$file_c = str_replace('F_date', '$this->dates->F_date',$file_c);
    
    $file_c = str_replace('F_', '$this->dates->F_', $file_c);
    
     
    $file_c = str_replace('money(', '$this->html->money(', $file_c);
    $file_c = str_replace('tablehead(', '$this->html->tablehead(', $file_c);
    $file_c = str_replace('tablefoot(', '$this->html->tablefoot(', $file_c);
    $file_c = str_replace('add_all_to_cart(', '$this->html->add_all_to_cart(', $file_c);
    $file_c = str_replace('exportcsv(', '$this->utils->exportcsv(', $file_c);
    $file_c = str_replace('sms2admin(', '$this->comm->sms2admin(', $file_c);
    $file_c = str_replace('dl_file(', '$this->utils->dl_file(', $file_c);
    $file_c = str_replace('show_hide(', '$this->html->show_hide(', $file_c);
    $file_c = str_replace('$ht->htlist(', '$this->html->htlist(', $file_c);
    $file_c = str_replace('sql2json(', '$this->utils->sql2json(', $file_c);
    $file_c = str_replace('linkalize(', '$this->utils->linkalize(', $file_c);
    
    
    $file_c = str_replace('utf8_cutByPixel(', '$this->utf8->utf8_cutByPixel(', $file_c);
    
    $file_c = str_replace('$tb->ShowAssos();', '$out.=$tb->ShowAssos();', $file_c);
    $file_c = str_replace('new ArrayTable();', 'new Table();', $file_c);
    
    $file_c = str_replace('get_val(', '$this->data->get_val(', $file_c);
    $file_c = str_replace('get_name(', '$this->data->get_name(', $file_c);
    $file_c = str_replace('detalize(', '$this->data->detalize(', $file_c);
    $file_c = str_replace('DB_log(', '$this->data->DB_log(', $file_c);
    $file_c = str_replace('table_exists(', '$this->data->table_exists(', $file_c);
    $file_c = str_replace('field_exists(', '$this->data->field_exists(', $file_c);
    $file_c = str_replace('rev_docs2obj(', '$this->data->rev_docs2obj(', $file_c);
    $file_c = str_replace('docs2obj(', '$this->data->docs2obj(', $file_c);
    $file_c = str_replace('show_docs2obj(', '$out.=$this->show_docs2obj(', $file_c);
    $file_c = str_replace('rev_listitems2obj(', '$this->data->rev_listitems2obj(', $file_c);
    $file_c = str_replace('listitems2obj(', '$this->data->listitems2obj(', $file_c);
    $file_c = str_replace('show_listitems2obj(', '$this->data->show_listitems2obj(', $file_c);
    $file_c = str_replace('isallowed(', '$this->data->isallowed(', $file_c);
    $file_c = str_replace('readconfig(', '$this->data->readconfig(', $file_c);
    $file_c = str_replace('writeconfig(', '$this->data->writeconfig(', $file_c);
    
    $file_c = str_replace('../assets', '".APP_URI."/assets', $file_c);
    
    $file_c = str_replace('DB_show(', '$out.=$this->show(', $file_c);
    $file_c = str_replace('DB_details(', '$out.=$this->details(', $file_c);
    $file_c = str_replace('DB_report(', '$out.=$this->report(', $file_c);
    $file_c = str_replace('DB_save(', '$out.=$this->save(', $file_c);
    
    $file_c = str_replace('global $db;', '', $file_c);
    $file_c = str_replace('$db,', '', $file_c);
    
    $file_c = str_replace('function $this->data->', 'function ', $file_c);
    $file_c = str_replace('function $this->db->', 'function ', $file_c);
    $file_c = str_replace('function $this->utils->', 'function ', $file_c);
    $file_c = str_replace('$out.= $out', '', $file_c);

//  $file_c = str_replace('textarea', 't extarea',$file_c);// comment out on forms!!!
    

    
    
    $func=explode($openf, $file_c);
    //echo $this->html->pre_display(htmlspecialchars($func,'ENT_COMPAT | ENT_XHTML','ISO-8859-1'));
    //echo $this->html->pre_display($func);
    foreach ($func as $function) {
        $fname=explode($closef, $function);
        if (($fname[0]!='')&&($i>0)) {
            $parts=explode("'", $fname[0]);
            $filename=$parts[0];
            echo "Function($i): ($filename) <a href='?act=$the_action&what=$filename'>$the_action</a><br>";
            
            //$chunk=explode($closef2,$function);
            
            $chunks=explode($closef2, $function);
            //$chunks=explode($openf,$function);
            $text="<?php\n$begin".$chunks[0];
            if ((!$this->utils->contains('$body.=', $text))&&($the_action!='functions')) {
                $text="$text\n\$body.=\$out;\n";
            }
            
            //file_tmp=$destination_dir."/--$filename.php";
            $file_tmp=$destination_dir."/$filename.php";
            $file=$destination_dir."/$filename.php";
            if ((!file_exists($file))&&(!file_exists($file_tmp))) {
                echo "$file<br>";
                echo "<textarea>$text</textarea><br>";
                //echo $this->html->pre_display($text);
                if (!file_put_contents($file_tmp, $text)) {
                    echo " ERR<br>";
                };
            }
            
            $files[$i]=$filename;
        }
        $i++;
    }
}
