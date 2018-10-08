<?php
$tmp=$this->html->readRQcsv('ids');
if ($tmp!='') {
    $sql.=" and id in ($tmp)";
}

$sql="select count(id) as hits, what as report from clicks where act='report' and what not in ('searchresults', 'favorites','usage') GROUP BY what order by hits desc";
$fields=array('#','hits','report');
$csv_arr[]=implode("\t", $fields);

$out=$this->html->tag("Report Usage", 'foldered');
$out.=$this->html->tablehead('', $qry, $order, $addbutton, $fields, 'autosort');
if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
$rows=pg_num_rows($cur);
$start_time=$this->utils->get_microtime();
while ($row = pg_fetch_array($cur, null, PGSQL_ASSOC)) {
    $i++;
    $this->progress($start_time, $rows, $i, "$what $i / $rows");
    $name=$this->data->detalize('lists', $row[id]);

    $out.= "<tr>";
    $out.= "<td>$i</td>";
    $out.= "<td class='n'>$row[hits]</td>";
    $json=$this->db->getval("SELECT get from clicks where act='report' and what='$row[report]' order by id desc limit 1");
    $json=json_decode($json, true);
    unset($json[plain]);
    unset($request);
    foreach ($json as $key => $value) {
        $request[]="$key=$value";
    }
    $link=implode("&", $request);
    $out.= "<td><a href='?$link'>$row[report]</a></td>";
    $used_reports[$row[report]]=$row[hits];
    $out.= "</tr>";
    $csv_arr[]=implode("\t", [$row[report], $row[hits]]);
}

//$csv=$this->utils->array_to_csv($csv_arr);


$this->livestatus('');
$out.=$this->html->tablefoot($i, $totals, $totalrecs);

$csv=implode("\n", $csv_arr);
$export=$this->utils->exportcsv($csv);
unset($csv_arr);
$out.=$export;

unset($used_reports['']);
//echo $this->html->pre_display($used_reports,"result");
$i=0;
$sql='';
$tmp=$this->html->readRQcsv('ids');
if ($tmp!='') {
    $sql.=" and id in ($tmp)";
}

$sql="SELECT * FROM menuitems WHERE (link like '%act=report%' or link like '%act=filter%' or link like '%&nopager=1%')and id in (select menuid from menus) $sql order by link";
$fields=array('#','report','name','hits');
$csv_arr[]=implode("\t", $fields);



$out.=$this->html->tag('From menue', 'foldered');
$out.=$this->html->tablehead('', $qry, $order, $addbutton, $fields, 'autosort');
if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
$rows=pg_num_rows($cur);
$start_time=$this->utils->get_microtime();
//if($rows>0)$csv=$this->data->csv($sql);
while ($row = pg_fetch_array($cur, null, PGSQL_ASSOC)) {
    $i++;
    $this->progress($start_time, $rows, $i, "$what $i / $rows");
    $arr=explode('&', $row[link]);
    unset($request);
    foreach ($arr as $value) {
        $keys=explode('=', $value);

        $request[$keys[0]]=$keys[1];
    }

    $name=$request[what];
    if ($request[table]!='') {
        $name=$request[table];
    }
    //echo $this->html->pre_display($row,"result");
    $out.= "<tr>";
    $out.= "<td>$i</td>";
    $out.= "<td>$name</td>";
    if (!$used_reports[$name]) {
        //$row[link]='#';
    }
    $out.= "<td><a href='$row[link]'>$row[name]</a></td>";
    $out.= "<td>".$used_reports[$name]."</td>";
    $out.= "<td><a href='?act=edit&what=menuitems&id=$row[id]'>E</a></td>";
    $out.= "</tr>";
    $csv_arr[]=implode("\t", [$name, $used_reports[$name]]);
}
$this->livestatus('');
$out.=$this->html->tablefoot($i, $totals, $totalrecs);

$csv=implode("\n", $csv_arr);
$export=$this->utils->exportcsv($csv);
unset($csv_arr);
$out.=$export;

$body.=$out;
