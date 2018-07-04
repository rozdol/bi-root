<?php
if ($what == 'sendmail'){
					$myemail=$this->db->GetVal("select email from users where id=$uid");
					$username=$this->db->GetVal("select username from users where id=$uid");
					$i=0;
					$rus='100';
					$subject=$this->html->readRQ('subject');
					$mailtext=$this->html->readRQ('descr');
					$file_array = array();
					foreach ($_POST as $key => $value) {
						$out.= $key . " => " . $value . "<br>\n";
						if(substr($key,0,4)=='item'){
							//$id=substr($key,4); 
							$id=$value; 
							$res=$this->db->GetRow("select * from uploads where id=$id");

							$out.= "<b>$id</b> $uploaddir/$res[filename]<br>";
							$filenames.="$res[filename],";
							array_push($file_array,array('file'=>"$uploaddir/$res[filename]",
								'mimetype'=>"$res[filetype]",
								'filename'=>"$res[filename]"));
							$i++;
						}

						//print_r($file_array);

						if($key=='sendrus'){$rus='1';}
						if($key=='sendeng'){$rus='0';}
						//$out.= "Rus= [$rus]... <br>";
						if($rus<100){
							$out.= "Sending as spam [$rus]... <br>";
							$sql="select email from partners where spam='1' and email!='' and rus='$rus'";
							if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
							while ($row2 = pg_fetch_array($cur)) {
								//$out.= "$row[email]<br>";
								mail_attached($row2[email], $myemail, "$subject","$mailtext",  $file_array);   
								$out.="sent to $email <br>"; 	
							}
							exit;	
							$rus='100';

						}
						if($key=='email'){
							$email=$value;
							$email=str_ireplace(" ", "",$email);
							$email=str_ireplace(",", ";",$email);
							$emaillist=explode(";",$email);
							foreach ( $emaillist as $address ) {
								$out.= "Sending to $address ... <br>";
								mail_attached($address, $myemail, "$subject","$mailtext",  $file_array);	
							}		
						}

					}

					$out.= "<pre>".print_r($file_array)."</pre>";
					$logtext.="E-mail from $username to $address subject:[$subject], files:$filenames";
					//exit;

				}
				
$body.=$out;
