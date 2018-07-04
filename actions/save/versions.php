<?php
if ($what == 'versions'){
					$id=($this->html->readRQ('id')*1);
					$name=($this->html->readRQ('name'));
					$descr=($this->html->readRQ('descr'));

					if ($id<>0){
						$sql="update $what SET 
							name='$name',
							descr='$descr'
							WHERE id='$id';";	
						$cur= $this->db->GetVal($sql);
					}else{ 
						$sql="insert into $what (
							name,
							descr,
							date
						) VALUES (
							'$name',
							'$descr',
							now()
							);";
						$cur= $this->db->GetVal($sql);
						$id=($this->db->GetVal("select max(id) from $what")*1);
					}
				}
				
$body.=$out;
