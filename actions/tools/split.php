<?php
if (!$access['main_admin'])$this->html->error('Honey pot');
//--------------------------
if (($what == 'split')&&($access['main_admin'])){

	$the_action='functions';
	$mod='acc';
	if($the_action=='report')$suffix="s";
	//define(OLD_APP_DIR, '/Library/PostgreSQL/EnterpriseDB-ApachePhp/apache/www/bi/consumers/szc');
	//define(OLD_FW_DIR, '/Library/PostgreSQL/EnterpriseDB-ApachePhp/apache/www/bi/bi-framework');
	
	define(OLD_FW_DIR, '/www/bi/bi-framework');
	define(OLD_APP_DIR, '/www/bi/consumers/szc');
	
	//consumers
	$source_file=APP_DIR."/classes/consumer.php";
	//$destination_dir=APP_DIR."/procedures/$the_action";
	$destination_dir=APP_DIR."/functions";
	
	if(!file_exists($source_file))$this->html->error("no file $source_file");
	$file_c = file_get_contents($source_file);
	
	$openf="function ";
	$closef="(";
	$closef2="function ";
	$begin="function ";
	
	$func=explode($openf,$file_c);
 	//echo $this->html->pre_display(htmlspecialchars($func,'ENT_COMPAT | ENT_XHTML','ISO-8859-1'));
	//echo $this->html->pre_display($func);
	foreach($func as $function){
		$fname=explode($closef,$function);
		if(($fname[0]!='')&&($i>0)){
			$parts=explode("'",$fname[0]);
			$filename=$parts[0];
			echo "<h3>Function($i): ($filename) <a href='?act=$the_action&what=$filename'>$the_action</a></h3>";
			
			//$chunk=explode($closef2,$function);
			
			$chunks=explode($closef2,$function);
			//$chunks=explode($openf,$function);
			//$text="<?php\n$begin".$chunks[0];
			$text=$chunks[0];
			
			$text1=$chunks[0];
			$chunks2=explode("{",$chunks[0]);
			$text1="\tfunction $chunks2[0]{\$func_file=APP_DIR.DS.'/functions/'.__FUNCTION__.'.php';if(file_exists(\$func_file))return include(\$func_file);}\n";
			$text=str_replace("$chunks2[0]{", '',$text);
			$text="<?php\n".substr($text, 0, -4);
			$list.=$text1;
			$file_tmp=$destination_dir."/$filename.php";
			$file=$destination_dir."/$filename.php";
			if((!file_exists($file))&&(!file_exists($file_tmp))){
				echo "<h5>$file_tmp</h5>";
				echo "<textarea>$text</textarea><br>";
				//echo $this->html->pre_display($text);
				if(!file_put_contents($file_tmp, $text))echo "<pre class='red'>ERR: $file_tmp</pre><br>";;
			}
			
			$files[$i]=$filename;
		}
		$i++;
	}
	echo "<textarea>$list</textarea><br>";
	
}