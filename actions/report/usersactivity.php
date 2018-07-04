<?php
if ($what == 'usersactivity'){
				$fields=array('id', 'username','today','yesterday', '3 days ago', '4 days ago', '5 days ago', '6 days ago', '7 days ago', '8 days ago' , '9 days ago', '10 days ago', '11 days ago', '12 days ago', '13 days ago' ,'last 30 days');
				$out.=$this->html->tablehead('','', '', '', $fields);
				//$out.=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);
				//$sql="select * from logs where action like 'LOGIN%' order by id desc limit 20";
				//$sql="select * from logs where action like '%act=login&%' order by id desc limit 40";
				$sql="select * from users where id>0 and active>=1 order by id";
				if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}	
				while ($row = pg_fetch_array($cur)) {
					$out.= "<tr>";
					  $today=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date");
					$yesterday=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '1 day' and date<=current_date - INTERVAL '0 day'");
					$yesterday3=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '2 day' and date<=current_date - INTERVAL '1 day'");
					$yesterday4=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '3 day' and date<=current_date - INTERVAL '2 day'");
					$yesterday5=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '4 day' and date<=current_date - INTERVAL '3 day'");
					$yesterday6=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '5 day' and date<=current_date - INTERVAL '4 day'");
					$yesterday7=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '6 day' and date<=current_date - INTERVAL '5 day'");
					$yesterday8=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '7 day' and date<=current_date - INTERVAL '6 day'");
					$yesterday9=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '8 day' and date<=current_date - INTERVAL '7 day'");
					$yesterday10=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '9 day' and date<=current_date - INTERVAL '8 day'");
					$yesterday11=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '10 day' and date<=current_date - INTERVAL '9 day'");
					$yesterday12=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '11 day' and date<=current_date - INTERVAL '10 day'");
					$yesterday13=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '12 day' and date<=current_date - INTERVAL '11 day'");
					$last30=$this->db->GetVal("select count(*) from logs where userid=$row[id] and date>=current_date - INTERVAL '30 day'");
					$out.= "<td>$row[id]</td><td>$row[username]</td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&today=1'>".$this->html->money($today)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=1'>".$this->html->money($yesterday)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=2'>".$this->html->money($yesterday3)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=3'>".$this->html->money($yesterday4)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=4'>".$this->html->money($yesterday5)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=5'>".$this->html->money($yesterday6)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=6'>".$this->html->money($yesterday7)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=7'>".$this->html->money($yesterday8)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=8'>".$this->html->money($yesterday9)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=9'>".$this->html->money($yesterday10)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=10'>".$this->html->money($yesterday11)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=11'>".$this->html->money($yesterday12)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&dayshift=12'>".$this->html->money($yesterday13)."</a></td>
					<td class='n'><a href='?act=show&what=logs&uid=$row[id]&last30=1'>".$this->html->money($last30)."</a></td></tr>\n";    
					}
				$out.=$this->html->tablefoot($i,$totals,$totalrecs);
				$body.= "$out";
			}
		