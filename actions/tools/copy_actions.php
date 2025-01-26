<?php 
//?act=tools&what=copy_actions&from=is&type=table&object=events
//?act=tools&what=copy_actions&from=is&type=append&object=eventform_name
//?act=tools&what=copy_actions&from=is&type=function&object=get_events
if($GLOBALS['access']['main_admin']){
	$from=$this->html->readRQ('from');
	if($from=='')$from='is';
	$object=$this->html->readRQ('object');
	if($object=='')$object='events';
	$type=$this->html->readRQ('type');
	if($type=='')$type='table';


	$copided=0;
	define('REMOTE_APP_DIR', PROJECT_DIR . $from . DS);
	if($type=='append'){
		$actions_set=array('append');	
	}
	if($type=='function'){
		$actions_set=array('functions');
	}
	if($type=='table'){
		$actions_set=array('show','form','save','details','search');
	}


	foreach ($actions_set as $action) {
		if($type=='table'){
			$source_file=REMOTE_APP_DIR."actions".DS.$action.DS.$object.".php";
			if(!file_exists($source_file))$this->html->message("no file $source_file");
			$files[$action]=$source_file;
			$dest_file=APP_DIR."actions".DS.$action.DS.$object.".php";
			if((!file_exists($dest_file))&&(file_exists($source_file))){
				copy($source_file, $dest_file) or die("Unable to copy file $source_file to $dest_file!");
				$copided++;
			}
		}
		if($type=='append'){
			$source_file=REMOTE_APP_DIR."actions".DS.$action.DS.$object.".php";
			if(!file_exists($source_file))$this->html->message("no file $source_file");
			$files[$action]=$source_file;
			$dest_file=APP_DIR."actions".DS.$action.DS.$object.".php";
			if((!file_exists($dest_file))&&(file_exists($source_file))){
				copy($source_file, $dest_file) or die("Unable to copy file $source_file to $dest_file!");
				$copided++;
			}
		}
		if($type=='function'){
			$source_file=REMOTE_APP_DIR.$action.DS.$object.".php";
			if(!file_exists($source_file))$this->html->message("no file $source_file");
			$files[$action]=$source_file;
			$dest_file=APP_DIR.$action.DS.$object.".php";


			$source_project_file=REMOTE_APP_DIR."classes".DS."project.php";
				$file_c = file_get_contents($source_project_file) or die("Unable to open file! $source_project_file");
				$searchfor = "function $object(";
				$pattern = preg_quote($searchfor, '/');
				$pattern = "/^.*$pattern.*\$/m";
				if(preg_match_all($pattern, $file_c, $matches)){
					$f_header=implode("\n\t", $matches[0]);
				}
				echo $this->html->pre_display($f_header,"line");

				
			if((!file_exists($dest_file))&&(file_exists($source_file))){
				copy($source_file, $dest_file) or die("Unable to copy file $source_file to $dest_file!");
				$copided++;


				$project_file=APP_DIR."classes".DS."project.php";
				$file_c = file_get_contents($project_file) or die("Unable to open file! $project_file");
				$file_c=str_replace("//replace_placeholder", "$f_header\n\t//replace_placeholder",$file_c);
				file_put_contents($project_file, $file_c) or die("Unable to save file! $project_file");

			}

		}
	}
	echo $this->html->pre_display($files,"files $copided copied");
}