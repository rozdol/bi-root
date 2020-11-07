<?php
if($what=="uploads"){
	$forever=$this->html->readRQn('forever');
	$uploaddir=DATA_DIR.'docs';

	$userid=$this->db->GetVar("select userid from uploads where id=$id");
	if(($uid<>$userid)&&!($access['main_admin'])&&!($access['delete_uploads'])) {$this->html->error("<br><b>No Permission<br>File is not uploaded by you.</b>" );}
	$uploads=$this->db->GetRow("select * from uploads where id=$id");

	if($uploads[id]==$id){
		//echo $this->html->pre_display($uploads,"uploads");
		//exit;
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

			$s3_fullname_parts=explode(DS,$fullname);
			array_shift($s3_fullname_parts);
			$s3_fullname=implode(DS,$s3_fullname_parts);
			//echo "Using AWS S3 for $s3_fullname<br>";
			//
			//echo $this->html->pre_display($fullname,"fullname"); exit;
			//$fullname=$deletedfiles."/$docname/".$filename;
		}

		$deletedfilename=$filename;
		$deletedfile=$deletedfiles."/$docname/".$deletedfilename;

		$s3_fullname_parts=explode(DS,$deletedfile);
		array_shift($s3_fullname_parts);
		$s3_deleted_fullname=implode(DS,$s3_fullname_parts);

		//echo $this->html->pre_display($s3_deleted_fullname,"s3_deleted_fullname"); exit;

		if(getenv('AWS_USE_S3')==1){
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

			if($forever>0){
				try{
					$s3->deleteObject([
					    'Bucket' => getenv('AWS_S3_BUCKET'),
					    'Key'    => $s3_fullname
					]);
				} catch (Aws\S3\Exception\S3Exception $e) {
				    $message=$e->getMessage();
				    $parts=explode('<?xml version="1.0" encoding="UTF-8"?>',$message);
				    $error=$parts[2];
				    $xml = new SimpleXMLElement($error);
				    $this->html->error($xml->Message);
				}
				$sql="delete from uploads where id=$id";
			}else{
				try{
					$s3->copyObject([
					    'Bucket' 	 => getenv('AWS_S3_BUCKET'),
					    'Key'        => $s3_deleted_fullname,
					    'CopySource' => getenv('AWS_S3_BUCKET')."/{$s3_fullname}",
					]);
				} catch (Aws\S3\Exception\S3Exception $e) {
				    $message=$e->getMessage();
				    //$sql="update uploads set descr='(deleted)'||descr, active='f' where id=$id";
				    //$this->db->GetVal($sql);
				    //echo $this->html->pre_display($message,"message"); exit;
				    $parts=explode('<Error>',$message);
				    //echo $this->html->pre_display($parts,"parts");
				    $error=$parts[1];
				    $this->html->error($error);
				}
				try{
					$s3->deleteObject([
					    'Bucket' => getenv('AWS_S3_BUCKET'),
					    'Key'    => $s3_fullname
					]);
				} catch (Aws\S3\Exception\S3Exception $e) {
				    $message=$e->getMessage();
				    $parts=explode('<?xml version="1.0" encoding="UTF-8"?>',$message);
				    $error=$parts[2];
				    $xml = new SimpleXMLElement($error);
				    $this->html->error($xml->Message);
				}
				$sql="update uploads set descr='(deleted)'||descr, active='f' where id=$id";
			}
			//echo "Moved $s3_fullname -> $s3_deleted_fullname<br>";

		}else{
			$sql="update uploads set descr='(deleted)'||descr, active='f' where id=$id";
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
					$out.= "<br>$fullname<br>$deletedfile";
					$out.="<br>Moving from $fullname to $deletedfile";
					if (rename($fullname,$deletedfile)){
						$sql="update uploads set descr='(deleted)'||descr, active='f' where id=$id";
					}else{
						$sql="select 1 as result";
						$out.="<br>Not deleted.";
						echo $out; exit;
					}
				}
			}else{
				if(file_exists($deletedfile)){
					$out.= "<br>File $fullname is deleted before";
					if($forever>0){
						if (unlink($deletedfile)){
							$sql="delete from uploads where id=$id";
						}
					}

				}else{
					$out.= "<br>File $fullname DOES NOT EXIST";
					$sql="delete from uploads where id=$id";
				}
				//$sql="update uploads set descr='(deleted)'||descr, active='f' where id=$id";

			}
		}

		if($uploads[tablename]=='documents'){$tmp=$this->db->GetVal("update documents set uploads=uploads-1 where id=$uploads[refid]");}

		//echo $out; exit;
		//delete_upload($fullname);
		//delete_upload($thmb);
	}else{
		$out.="<br>No record found with id $id";
	}
	echo "$out<br>";
}