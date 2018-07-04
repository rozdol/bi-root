<?php
if ($what == 'forms'){
					$rows=0;	
					$success=1; 
					$data=$this->html->readRQc('data');
					//$data=str_ireplace("\t", ";",$data);
					$records=explode("\r",$data);
					foreach ( $records as $rec ) {     
						$parts=explode(";",$rec);
						$parts[0]=str_ireplace("\n", "",$parts[0]);
						$parts[0]=str_ireplace("\t", "",$parts[0]);
						$parts[0]=str_ireplace(",", "",$parts[0]);
						if (strlen($parts[0])>0){
							//$out.= "$parts[0]<br>";
							if (strlen(stristr($parts[0],"CREATE TABLE "))>0){
								$row=explode("CREATE TABLE ",$parts[0]);	
								//$out.= "($row[1])<br>";
								$row=explode(" ",$row[1]);
								//$out.= "($row[0])<br>";
								$table=$row[0];
							}else{
								$row=explode(" ",$parts[0]);
								$fname=$row[0];
								$ftype=$row[1];
								//$out.= "($row[0])($row[1])<br>";
								if($ftype=="date")$out.="<label>".ucfirst($fname)."</label><input name='$fname' value='\$res[$fname]' data-datepicker='datepicker' class='date' type='text' placeholder='DD.MM.YYYY'/>\n";
								$out.="<label>".ucfirst($fname)."($ftype)</label><input type='text' name='$fname'  value='\$res[$fname]'>\n";
							}

						}
					}
					$out.= "<textarea name='descr' >$out</textarea>";
				}
				//exit;
				
$body.=$out;
