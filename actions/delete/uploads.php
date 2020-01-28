<?php
if($what=="uploads"){
	$forever=$this->html->readRQn('forever');
	$uploaddir=DATA_DIR.'/docs';
	$userid=$this->db->GetVar("select userid from uploads where id=$id");
	if(($uid<>$userid)&&!($access['main_admin'])&&!($access['delete_uploads'])) {$this->html->error("<br><b>No Permission<br>File is not uploaded by you.</b>" );}
	$uploads=$this->db->GetRow("select * from uploads where id=$id");
	$filename=$uploads[filename];
	//$thmb=$progdir."/".$this->data->readconfig('thumbdir')."/".$filename;
	$fullname=$uploaddir."/".$filename;
	if($uploads[tablename]=='documents'){
		$deletedfiles=DATA_DIR.'docs/deleted';
		$docname=$this->db->GetVal("select name from documents where id=$uploads[refid]");
		
		$dirs=explode("-",$docname);
		$y=$dirs[0];
		$m=$dirs[1];
		$d=$dirs[2];

		$newdir=$deletedfiles."/$y/$m/$d";
		if (!is_dir($newdir)) {
		    if(!mkdir($newdir,0755, true)){
		        $this->html->error("Failed to make directory $newdir");
		    }
		}
	
		$docname=str_replace("-","/",$docname);
		$directory=$deletedfiles."/$docname";
		
		
		
		$docname=str_replace("-","/",$docname);
		$fullname=$uploaddir."/$docname/".$filename;
		//$fullname=$deletedfiles."/$docname/".$filename;
	}
	
	$deletedfilename=$filename;
	$deletedfile=$deletedfiles."/$docname/".$deletedfilename;
	$out.= "<br>$fullname<br>$deletedfile";
	$out.="<br>Moving from $fullname to $deletedfile";
	if(file_exists($fullname)){
		if($forever>0){
			if (unlink($fullname)){
				$sql="delete from uploads where id=$id";
			}else{
				$sql="select 1 as result";
				$out.="$fullname<br>Not deleted.";
				echo $out; exit;
			}
		}else{
			if (rename($fullname,$deletedfile)){
				$sql="update uploads set descr='(deleted)'||descr, active='f' where id=$id";
			}else{
				$sql="select 1 as result";
				$out.="<br>Not deleted.";
				echo $out; exit;
			}
		}
	}else{
		$sql="update uploads set descr='(deleted)'||descr, active='f' where id=$id";
		$out.= "<br>File $fullname DOES NOT EXIST";
	}
	
	
	if($uploads[tablename]=='documents'){$tmp=$this->db->GetVal("update documents set uploads=uploads-1 where id=$uploads[refid]");}
	$sql="update uploads set descr='(deleted)'||descr, active='f' where id=$id";
	//echo $out; exit;
	//delete_upload($fullname);
	//delete_upload($thmb);
}