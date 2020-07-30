<?php
if ($access['main_admin']){
	$delim =$this->html->readRQc('delim');
	$id=$this->html->readRQ("id")*1;
	if($delim==''){$delim=";";}
	$hideaccounts=$this->html->readRQn('hideaccounts');
	if($hideaccounts==0){
		if($sql==''){$sql="select * from a_accounts where partnerid=$id order by number asc";}
		if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
		while ($row = pg_fetch_array($cur)) {
		   	    $parent=$this->project->get_a_number($row[parentid]);
				$number=$row[number];
				$name=$row[name];
				$header=$row[header];
				$curr=$row[currency];
				$descr=$row[descr];
				$cash=$row[cash];
				$budget=$row[budget];
				$bank_account_id=$row[bank_account_id];
				$active=$row[active];
				$cumulative=$row[cumulative];
				$internal_code=$row[internal_code];
				$soft_code=$row[soft_code];
			   $response.=str_replace(["\n","\r"]," ","$parent;$number;$name;$header;$curr;$descr;$cash;$budget;$bank_account_id;$active;$cumulative;$internal_code;$soft_code")."\n";
			}
	}

	$sql='';
	$df=$this->html->readRQd('df');
	if($df!=''){$sql.=" and date>='$df'";}

	$dt=$this->html->readRQd('dt');
	if($dt!=''){$sql.=" and date<='$dt'";}

	$a_account_id=$this->html->readRQn('a_account_id');
	if($a_account_id>0){$sql.=" and id in (select a_transactionid from a_translines where a_accountid=$a_account_id)";}

	$sql="select * from a_transactions where partnerid=$id $sql order by name asc, sorting asc, id asc";
	$transactions=$this->db->getval("select count(*) from a_transactions where partnerid=$id")*1;
	$lines=$this->db->getval("select count(*) from a_translines where a_transactionid in (select id from a_transactions where partnerid=$id)")*1;
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	while ($row = pg_fetch_array($cur)) {
			$j++;
			$date=$row[date];
			$name=$row[name];
			$addinfo=$row[header];
			$curr=$row[currency];
			$descr=$row[descr];
			$type=$row[type];
			$amount=$row[amount];
			$rate=$row[rate];
			$transaction_id=$row[transaction_id];
			$sql="select * from a_translines where a_transactionid=$row[id] order by line asc";
			if (!($cur2 = pg_query($sql))) {$this->html->SQL_error($sql);}
			while ($row2 = pg_fetch_array($cur2)) {
				$line=$row2[line];
				$dr=$row2[drtrans];
				$cr=$row2[crtrans];
				$accnumber=$this->project->get_a_number($row2[a_accountid]);
				$not_fx=$row2[not_fx];
				//$rate=$row2[rate];
				$qty=$row2[qty];
				$i++;
				$response2.=str_replace(["\n","\r"]," ","$date;$name;$type;$curr;$rate;$transaction_id;$line;$dr;$cr;$accnumber;$qty;$not_fx;$descr;$addinfo")."\n";;
			}
		   //$response2.="$date,$name,$type,$amount,$curr,$rate,($line,$dr,$cr,$accnumber),$descr,$addinfo\n";
  		}
		$response2.="$date;END;";
		$response2.="$date;TR:$transactions/$j, LN:$lines/$i;\n";
if ($wrap==1){
  	header("Content-type: text/txt");
  	header("Content-Disposition: attachment; filename=\"result.txt\"");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Cache-Control: cache, must-revalidate");
  header("Pragma: public");
  header("Content-Length: " . strlen($response));
    echo $response;
  }
  else
  {

	if($hideaccounts==0)echo "
		<form action='?csrf=$GLOBALS[csrf]&act=save&table=importaccounting'  method='post' name='Form1' id='Form1'>
		<input type='hidden' name='id' value='$id'>
		<input type='hidden' name='debug' value='0'>
		<input type='hidden' name='delim' value='$delim'>";
	if($hideaccounts==0)echo "<h3>Accounts</h3>
	<p>parent; number; name; header; curr; descr; cash; budget;bank_account_id;internal_code;soft_code</p>
	<textarea cols=150 rows=30 name='accounts' class='span12'>$response</textarea><br>";

	echo "<h3>Transactions</h3>
	<p> date;name;type;curr;rate;transaction_id;line;dr;cr;accnumber;qty;not_fx;descr;addinfo</p>
	<textarea cols=150 rows=30 name='transactions' class='span12'>$response2</textarea><br>";
	if($hideaccounts==0)echo "
	<label><input type='checkbox' name='reset' value='1' /> Reset Accounting?</label><br>
	<input type='submit' value='save' class='btn btn-primary' $warning>
	</form>";

	$fp=fopen("tmp.txt","w");//overwrite existing text file
fwrite($fp,$response);//write post-data
fclose($fp);
	//echo "<br><a href='tmp.txt'>Open it</a>";
  }	

}

