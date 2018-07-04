<?php
$sql="";
$help=$this->html->readRQn('help');
if ($help) {
    $func=$this->html->readRQs('func');
    $arr=[
        "user"=> "admin",
        "api_key"=> "dff1d9-1ef5e0-f37396-701ffc-6deff8",
        "func"=> $func,
        "table"=>"users"
    ];
    $new_vals=['Help'=>$func,'sample'=>$arr];
    $JSONData=$new_vals;
    return $JSONData;
}

$table=$this->html->readRQs('table');

if (!$table) {
    echo json_encode(['error'=>'No `table` defined']);
    exit;
}

if (!$GLOBALS["access"]["view_$table"]) {
    echo json_encode(['error'=>'No access']);
    exit;
}


$vals=$this->html->readRQj('values');


//return $JSONData=['ok'=>'ok'];

if ($this->data->field_exists($table, 'user_id')) {
    $sql.=" and user_id=$GLOBALS[uid]";
}


//return $JSONData=['ok'=>'ok'];

$sql="SELECT * from $table where id>0 $sql order by id asc";

$result=$this->db->GetResults($sql);
$pd= $this->html->pre_display($result, "result");
return $JSONData=[$table=>$result];

return $JSONData=$result;
