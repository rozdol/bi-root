<?php
if ($what == 'searchresults'){

		$text=strtolower($this->html->readRQ('text'));
		$type=substr($text,1,1);
		if($type==":"){
			$type=substr($text,0,2);
			$tokens=explode(":",$text);
			$count=count($tokens);
			$text=$tokens[($count-1)];
			unset($_POST);
			if($type=="p:"){//Partners
				$_POST[nopager]=1;
				$_POST[text]=$text;
				$out.=$this->show('partners');		
			}
			if($type=="d:"){//Documents
				$text=str_replace("\\","-",$text);
				$text=str_replace("/","-",$text);
				$text=str_replace(".","-",$text);
				$text=str_replace("#","-",$text);
				if($count>2){$field=$tokens[($count-2)];$_POST[$field]=$text;
				}else{$_POST[name]=$text;}
				$out.=$this->show('documents');		
			}
			if($type=="t:"){//Transacactions
				if($count>2){$field=$tokens[($count-2)];$_POST[$field]=$text;
				}else{$_POST[name]=$text;}
				$out.=$this->show('transactions');		
			}
			if($type=="r:"){//Client Request
				$_POST[value]=$text;
				$out.=$this->show('clientrequests');		
			}
			if($type=="a:"){//Account
				if($count>2){$field=$tokens[($count-2)];$_POST[$field]=$text;
				}else{$_POST[name]=$text;}
				$out.=$this->show('accounts');		
			}
			if($type=="i:"){//Invoice
				if($count>2){$field=$tokens[($count-2)];$_POST[$field]=$text;
				}else{$_POST[name]=$text;}
				$out.=$this->show('invoices');		
			}
			if($type=="j:"){//projects
				$_POST[value]=$text;
				$out.=$this->show('projects');		
			}
			if($type=="s:"){//assets
				$_POST[text]=$text;
				$out.=$this->show('p_assets');		
			}
			if($type=="u:"){//userdetails
				$_POST[action]=$text;
				$_POST[nomaxid]=1;
				$out.=$this->show('usersdet');		
			}
			if($type=="x:"){//SQL
				if($access[main_admin]){
					$sql=$text;
					if($sql==''){$sql="select * from logs order by id desc limit 100";}
					$sqltokens=explode("from ",$sql);
					$sqltokens2=explode(" ",$sqltokens[1]);
					$table=$sqltokens2[0];
					$sql=str_ireplace("\'","'",$sql);
					$out.= "$sql<br>";
					if (!($result = pg_query($sql))) {$this->html->SQL_error($sql);}	
					$fields_num = pg_num_fields($result);
					$response="";
					$tbl.="<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull'>";
					$tbl.="<tr class='c'>";
					for($i=0; $i < $fields_num; $i++)
					{
						$field = pg_field_name($result, $i);
						$response.="$field\t";
						$tbl.="<td>$field</td>";
						if($field=='id'){$idno=$i;}
					}
					$response.="\n";
					$tbl.="</tr>";
					$tbl.="<tr>";
					while($row = pg_fetch_row($result))
					{
						$i=0;
						foreach($row as $cell){
							$response.="$cell\t";
							if($i==$field)$cell="<a href='?act=details&what=$table&id=$cell'>$cell</a>";
							$tbl.="<td>$cell</td>";
							$i++;
						}
							$tbl.="</tr>";
							
						$response.="\n";
					}
					$tbl.="</table>";
					$export= "	
					<div class='dropdown2 dropdown-toggle' data-toggle='dropdown2'>.
					      <div class='dropdown-menu2'>
					      <textarea cols='1' rows='1'>$response</textarea>
						  </div>
					    </div>";
					$out.= "$tbl $export";

				}		
			}
			
		}else{
			$text=strtolower($this->html->readRQ('text'));
			if(strlen($text)>3){
				$words=explode(' ',$text);

				$all_tavles=$this->data->get_list_array("SELECT relname FROM pg_class WHERE NOT relname ~ 'pg_.*' AND NOT relname ~ 'sql_.*' AND relkind = 'r' ORDER BY relname");
				$sys_tables=explode(',','accessitems,accesslevel,blacklist_ip,comments,config,dbchanges,docs2groups,docs2obj,documentactions,drugs2prescriptions,failed_logins,fastmenu,favorites,groups,help,listitems,lists,logs,menue2group,menuitems,menus,schedules,tableaccess,uploads,user_group,useralerts,users,warnings');
				$project_tables=array_diff($all_tavles,$sys_tables);
				//echo $this->html->pre_display($all_tavles,'$all_tavles');
				//echo $this->html->pre_display($sys_tables,'$sys_tables');
				//echo $this->html->pre_display($project_tables,'$project_tables');

				foreach($project_tables as $table){
					$sql='select 0;';
					if($this->data->field_exists($table,'name')) $sql="select count(*) from $table where lower(name) like '%$text%'";
					$count=$this->db->GetVal($sql);
					if($count>0){
						$ids=$this->data->get_list_csv("select id from $table where lower(name) like '%$text%'");
						$out.="<a href='?act=show&what=$table&ids=$ids&nopager=1' class='white'><span class='badge'>$count</span></a> in $table</br>";
					}else{
						$sql_add='';
						if($this->data->field_exists($table,'descr')){
							foreach($words as $word){
								$sql_add.=" and lower(descr) ~* '$word'";
							}
							$sql="select count(*) from $table where id>0 $sql_add";
							$count=$this->db->GetVal($sql);
							if($count>0){
								$ids=$this->data->get_list_csv("select id from $table where id>0 $sql_add");
								$out.="<a href='?act=show&what=$table&ids=$ids&nopager=1' class='white'><span class='badge'>$count</span></a> in $table</br>";
							}else{
								$sql_add='';
								if($this->data->field_exists($table,'addinfo')){
									foreach($words as $word){
										$sql_add.=" and lower(addinfo) ~* '$word'";
									}
									$sql="select count(*) from $table where id>0 $sql_add";
									$count=$this->db->GetVal($sql);
									if($count>0){
										$ids=$this->data->get_list_csv("select id from $table where id>0 $sql_add");
										$out.="<a href='?act=show&what=$table&ids=$ids&nopager=1' class='white'><span class='badge'>$count</span></a> in $table</br>";
									}
								}
							}
						}
					}


				}
			}else{$out.= "<div class='alert alert-error'>Search string should be more than 3 characters long.</div>";}	
		}
		$table="";
		if($out=='')$out.= "<div class='alert alert-error'><b>$text</b> not found.</div>";
		//$out.= "$text, $type";
	}

	
		
$body.=$out;
