<?php
// $arr=[
//     "user"=> "admin",
//     "api_key"=> "dff1d9-1ef5e0-f37396-701ffc-6deff8",
//     "func"=> $func,
//     "what"=>"rate",
//     "pair"=>"eur/usd",
//     "date"=>"01.01.2018"
// ];
// $new_vals=['Help'=>$func,'sample'=>$arr];
// $JSONData=$new_vals;

$date=$this->html->readRQd('date', 1);
$pair=$this->html->readRQs('pair');
$currecies=explode('/',$pair);

$out.="$pair";
$out.=$this->html->pre_display($currecies,"currecies");

$query = QB::table('listitems')
    ->where('list_id', 6)
    ->where(QB::raw('LOWER(name) LIKE ?', $currecies[0]));
$curr_id1 = get_object_vars($query->first())[id];

$query = QB::table('listitems')
    ->where('list_id', 6)
    ->where(QB::raw('LOWER(name) LIKE ?', $currecies[1]));
$curr_id2 = get_object_vars($query->first())[id];
$rate=$this->data->convert_currency(1,$curr_id1,$curr_id2, $date);

$out.= $this->html->pre_display($rate,"rate");
$new_vals[date]=$date;
$new_vals[pair]=$pair;
$new_vals[rate]=$rate;


$JSONData=$new_vals;
$body.=$out;