<?php
if ($what == 'workinghours') {
            $userid=($this->html->readRQ('id'))*1;
    if ($userid==0) {
        $userid=$uid;
    }
            //$userid=0 ? $uid : $this->html->readRQ('id');
            $name=$this->db->GetVal("select firstname||' '||surname from users where id=$userid");
            $today=$this->dates->F_date($this->html->readRQ('df'), 1);
            $daysback=14;
            $day=$today;
            $nextdate=$this->dates->F_dateadd($day, $daysback);
            $prevdate=$this->dates->F_dateadd($day, -1*$daysback);
            $out.= "<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull' id='sortableTable'>";
            $out.= "<tr class='c2'><td colspan='15'>Working hours of<br>$name</td></tr>";
            $out.= "<tr class='c'><td>Day</td><td>From</td><td>To</td></tr>";
            
    for ($i=1; $i<=$daysback; $i++) {
        $weekday=$this->dates->F_weekday($day);
        if ($weekday<8) {
            $col_col = "b";
            if ($weekday>5) {
                $col_col = "h";
            }
            $dayname=$this->dates->F_weekdayname($day);
            $day2=$this->dates->F_dateadd($day, 1);
            $sql="select date from logs where userid=$userid and date>='$day' and date<'$day2' order by date asc limit 1";
            $start=$this->db->GetVal($sql);
            $sql="select date from logs where userid=$userid and date>='$day' and date<'$day2' order by date desc limit 1";
            $end=$this->db->GetVal($sql);
            $start=substr($start, 11);
            $end=substr($end, 11);
            $out.= "<tr class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">
					<td><a href='?act=report&what=workingday&plain=0&nomaxid=1&id=$userid&df=$day'>$day $dayname</a></td><td>$start</td><td>$end</td></tr>";
        }
        $day=$this->dates->F_dateadd($day, -1);
    }
    if ($plain>0) {
        $pager="<span onclick='ajaxFunction(\"workinghours_\",\"?act=report&what=$what&plain=1&nomaxid=1&id=$userid&df=$prevdate\");' onmouseover=\"this.style.cursor='pointer';\"><- Previous</span> . . . .
			<span onclick='ajaxFunction(\"workinghours_\",\"?act=report&what=$what&plain=1&nomaxid=1&id=$userid&df=$nextdate\");' onmouseover=\"this.style.cursor='pointer';\">Next -></span>";
    } else {
        $pager="<a href='?act=report&what=$what&plain=0&nomaxid=1&id=$userid&df=$prevdate'><- Previous</a> . . . <a href='?act=report&what=$what&plain=0&nomaxid=1&id=$userid&df=$nextdate'>Next -></a>";
    }
            $out.= "<tr class='c'><td colspan=15>$pager</td></tr>";
            $out.= "</table>";
}

    
    
$body.=$out;
