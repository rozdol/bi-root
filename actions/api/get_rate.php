<?php
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

$query = QB::table('listitems')
    ->where('list_id', 6)
    ->orderBy('id', 'ASC');
$result = $query->get();
foreach ($result as $row) {
    $currency=$row->name;
    $rate=$this->data->get_rate($row->id, $date);
    $new_vals[$currency]=$rate;
}

$JSONData=$new_vals;
