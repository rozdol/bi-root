<?php
if ($what == 'workingday') {
                $userid=($this->html->readRQ('id'))*1;
    if ($userid==0) {
        $userid=$uid;
    }
                //$userid=0 ? $uid : $this->html->readRQ('id');
                $name=$this->db->GetVal("select firstname||' '||surname from users where id=$userid");
                $today=$this->dates->F_date($this->html->readRQ('df'), 1);
                
                $day=$today;
                $day2=$this->dates->F_dateadd($day, 1);
                $nextdate=$this->dates->F_dateadd($day, 1);
                $prevdate=$this->dates->F_dateadd($day, -1);
                $prevhour=0;
                $sql="select date_trunc('hour',date) as date, count(*) as hits from logs where userid=$userid and date>='$day' and date<'$day2' group by date_trunc('hour',date) order by date_trunc('hour',date);";
                
                $out.= "<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull' id='sortableTable'>";
                $out.= "<tr class='c2'><td colspan='15'>$day Hits of<br>$name</td></tr>";
                $out.= "<tr class='c'><td>Day</td><td>From</td><td>To</td></tr>";
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    while ($row = pg_fetch_array($cur)) {
        $nbrow++;
        $col_col = "";
        $date=substr($row[date], 11);
        $hour=substr($date, 0, 2);
        $diff=$hour-$prevhour;
        if (($diff>1)&&($dispay>0)) {
            $prevhour3=$prevhour+1;
            $graph="";
            for ($i=1; $i<$diff; $i++) {
                //$col_col="x";
                $hour2=$prevhour3+$i;
                $prevhour2=$hour2-1;
                $out.= "<tr class='x' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='x'\">
							<td>$prevhour2 - $hour2</td><td>0</td><td>$graph</td></tr>";
            }
        }
        $dispay=1;
        $next=$hour+1;
        $graph="<p><img src='".ASSETS_URI."/assets/img/custom/bar.gif' height='10' width='$row[hits]'></p>";
            $out.= "<tr class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">						
						<td>$hour - $next</td><td>$row[hits]</td><td>$graph</td></tr>";
            $prevhour=$hour;
    }
    if ($plain>0) {
        $pager="<span onclick='ajaxFunction(\"workingday_\",\"?act=report&what=$what&plain=1&nomaxid=1&id=$userid&df=$prevdate\");' onmouseover=\"this.style.cursor='pointer';\"><- Previous</span> . . . .
				<span onclick='ajaxFunction(\"workingday_\",\"?act=report&what=$what&plain=1&nomaxid=1&id=$userid&df=$nextdate\");' onmouseover=\"this.style.cursor='pointer';\">Next -></span>";
    } else {
        $pager="<a href='?act=report&what=$what&plain=0&nomaxid=1&id=$userid&df=$prevdate'><- Previous</a> . . . <a href='?act=report&what=$what&plain=0&nomaxid=1&id=$userid&df=$nextdate'>Next -></a>";
    }
                $out.= "<tr class='c'><td colspan=15>$pager</td></tr>";
                $out.= "</table>";
}
    
$body.=$out;
