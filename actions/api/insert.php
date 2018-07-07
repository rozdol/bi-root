<?php
$help=$this->html->readRQn('help');
if ($help) {
    $func=$this->html->readRQs('func');
    $arr=[
        "user"=> "admin",
            "api_key"=> "dff1d9-1ef5e0-f37396-701ffc-6deff8",
            "func"=> "update",
            "table"=> "users",
            "id"=> "4",
            "values"=> ["surname"=>"Test"]
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

if (!$GLOBALS["access"]["edit_$table"]) {
    echo json_encode(['error'=>'No access']);
    exit;
}
$id=$this->html->readRQn('id');
$vals=$this->html->readRQj('values');

if ($this->data->field_exists($table, 'user_id')) {
    $vals[user_id]=$GLOBALS[uid];
}

//$id=$this->db->insert_DB($table, $vals);
$id = QB::table($table)->insert($vals);
//$new_vals=$this->data->get_row($table, $id);
$new_vals = QB::table($table)->find($id);
if (is_object($new_vals)) {
    $new_vals=get_object_vars($new_vals);
}
foreach ($vals as $key => $value) {
    $inserted[$key]=$new_vals[$key];
}
$new_vals = QB::table($table)->find($id);
if (is_object($new_vals)) {
    $new_vals=get_object_vars($new_vals);
}

$JSONData=[
    'user_id'=>$GLOBALS[uid],
    'table'=>$table,
    'id'=>$id,
    'inserted'=>$inserted,
    'record'=>$new_vals
];
