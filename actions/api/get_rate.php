<?php
//$new_vals=$this->db->getrow("SELECT * from rates order by date desc limit 1");
$help=$this->html->readRQn('help');
if ($help) {
    $func=$this->html->readRQs('func');
    $arr=[
        "user"=> "admin",
        "api_key"=> "dff1d9-1ef5e0-f37396-701ffc-6deff8",
        "func"=> "get_rate",
        "date"=>"01.01.2018"
    ];
    $new_vals=['Help'=>$func,'sample'=>$arr];
    $JSONData=$new_vals;
    return $JSONData;
}
$date=$this->html->readRQd('date', 1);

$new_vals['date']=$date;
$new_vals['help']=$help;
$sql2="select * from listitems where list_id=6 order by id";
if (!($cur2 = pg_query($sql2))) {
    $this->html->SQL_error($sql2);
}
while ($row2 = pg_fetch_array($cur2)) {
    $currency=$row2[name];
    $rate=$this->data->get_rate($row2[id], $date);
    $new_vals[$currency]=$rate;
}

$JSONData=$new_vals;
