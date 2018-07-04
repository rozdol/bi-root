<?php
$out.=$this->html->tag('Monitor', 'h1', '', 'header_id');
//HHD
$df = disk_free_space(".");
$ds = disk_total_space(".");
$du=$ds-$df;

$php_mem=ini_get('memory_limit');
$php_mem_peak=memory_get_peak_usage();
$php_mem_usage=memory_get_usage();


$array=array(
    '<b>Disk</b>'=>' ',
    'Disk Size'=>$this->utils->bytes2h($ds),
    'Disk Usage'=>$this->utils->bytes2h($du),
    'Disk Free'=>$this->utils->bytes2h($df),
    '<b>Memory</b>'=>'__________________',
    'Mem Limit'=>$php_mem,
    'Mem usage'=>$this->utils->bytes2h($php_mem_usage),
    'Mem Peak'=>$this->utils->bytes2h($php_mem_peak),
);

$array['<b>Blocked IP</b>']='__________________';
$sql="select ip, date_trunc('seconds', date_time) as date_time, descr from blacklist_ip  order by date_time; ";
if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
while ($row = pg_fetch_array($cur)) {
    $array[$user[date_time]]=$row[ip].' '.$row[descr];
}

$array['<b>Active users</b>']='__________________';
$sql="select id, date_trunc('seconds', lastvisit) as lastvisit from users where lastvisit>=now() - INTERVAL '20 minutes' order by lastvisit desc; ";
if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
while ($row = pg_fetch_array($cur)) {
    $user=$this->data->get_user_info($row[id]);
    $array[$user[full_name]]=$row[lastvisit];
}

$array['<b>Tables records</b>']='__________________';
$tables=array('partners', 'clientrequests', 'transactions', 'loans', 'documents','uploads');
foreach ($tables as $table) {
    if ($this->data->table_exists($table)) {
        $array[$table]=$this->db->GetVal("select count(*) from $table")*1;
    }
}

$col1=$this->html->pairs($array, '');



    $chart=array(
    "caption"=> "Disk",
    "subCaption"=> "",
    "numberPrefix"=> "",
    "showBorder"=> "0",
    "use3DLighting"=> "0",
    "enableSmartLabels"=> "0",
    "startingAngle"=> "310",
    "showLabels"=> "0",
    "showPercentValues"=> "1",
    "showLegend"=> "0",
    "defaultCenterLabel"=> $this->utils->bytes2h($ds),
    "centerLabel"=> $this->utils->bytes2h($ds),
    "centerLabelBold"=> "1",
    "showTooltip"=> "1",
    "decimals"=> "0",
    "useDataPlotColorForLabels"=> "1",
    "theme"=> "fint"
    );
    $data=array(
        array(
            "label"=> "Used",
            "value"=> $du
        ),
        array(
            "label"=> "Free",
            "value"=> $df
        ),
       
    );
    $FC_array=array('chart'=>$chart,'data'=>$data);

    $jsonEncodedData = json_encode($FC_array);
    $col2.= $this->utils->chart_js_new('doughnut2D', 300, 300, 'chart-1', $jsonEncodedData);

    $col2.=$this->report('long_requests');
    $rows=array($col1,$col2);
    $out.=$this->html->row($rows);

    if ($this->utils->contains('iPad', $_SERVER['HTTP_USER_AGENT'])) {
        $us="IPAD!!!";
    }
    $output .= "SERVER_SOFTWARE: $us\n".$_SERVER['SERVER_SOFTWARE']."\n\n";
    $output .= "USER_AGENT: $us\n".$_SERVER['HTTP_USER_AGENT']."\n\n";
    $output .= "SERVER:\n".shell_exec("uname -a")."\n\n";
    $output .= "UPTIME:\n".shell_exec("uptime")."\n\n";
    $output .= "DISK:\n".shell_exec("df -h")."\n\n";


//logins
    $out.=$this->data->sql_display("SELECT * FROM logs order by id desc limit 40", "Last Log");
    $out.=$this->data->sql_display("SELECT * FROM tableaccess order by id desc limit 40", "Table access");
    $out.=$this->data->sql_display("SELECT * FROM dbchanges order by id desc limit 40", "Data alterations");
//failded logins
//$out.=$this->data->sql_display("SELECT * FROM failed_logins order by id desc limit 40", "Failed logins");
//Hits

//project monitor
//$out.=$this->report('project_monitor');
    $out.=$this->html->pre_display($output, 'Info');
    $body="$out $nav $export";
