<?php
if ($what == 'testnewtablestyle'){
			$partnerid=($this->html->readRQ('id'))*1;
			$currid=($this->html->readRQ('curr'))*1; if($currid==0){$currid=$this->db->GetVal("select a_currency from partners where id=$partnerid");$_POST[curr]=$currid;}
			$currname=$this->db->GetVal("select name from listitems where id=$currid");
			$rate=$this->data->get_rate($currid);
			$out.= "<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull' id='sortableTable'>";
			$out.= "<tr class='rowheader'><td colspan='3' class='centered'>Accounting and Audit report</td></tr>";
			$out.= "<tr class='rowheader'><td>Partner</td><td>2000</td><td>2001</td></tr>";
			$sql="select * from partners where id>0 order by name limit 20";
			if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}	
			while ($row = pg_fetch_array($cur)) {
				$id=$row[id];
				$classid="row_$id";
				$nbrow++;
				if ($nbrow%2) {$roweven = "odd";} else {$roweven = "even";}
				$out.= "\t<tr class='colored $roweven' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='colored $roweven';\">\n";
				$out.= "<td class='bold'><a href='?act=edit&table=a_accounts&id=$row[id]'>$row[name]</a></td>
				<td class='number'>".$this->html->money($totals[0], '')."</td>
				<td class='right'>Ok</td><td class='hidden' onMouseOver=\"this.className='unhidden'\" onMouseOut=\"this.className='hidden';\" onClick='
				ajaxFunction(\"$classid\",\"?csrf=$GLOBALS[csrf]&act=append&what=test&value=$id\");'>Edit</td>";
				
				$out.= "</tr>";
				$out.= "<tr class='expandable'><td id='$classid' class='wrapped' colspan=3 onDblclick='this.innerHTML=\"\";'></td></tr>";
				$totals[0]+=1;
				$totals[1]+=2;
				
			}
			$totals[0]=round($totals[0],2);
			$totals[1]=round($totals[1],2);
			if($totals[0]==$totals[1])$ok="t"; else $ok="x";
			$out.= "<tr class='total'><td>Total:</td>
			<td class='number'>".$this->html->money($totals[0], '')."</td>
			<td class='number'>".$this->html->money($totals[1], '')."</td>
			</tr>";
			$out.= "</table>";
			

		}
		
$body.=$out;
