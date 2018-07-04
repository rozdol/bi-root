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
if (!$id) {
    echo json_encode(['error'=>'No `id` defined']);
    exit;
}

$vals=$this->html->readRQj('values');
//return $JSONData['values']=$vals;

if ($this->data->field_exists($table, 'user_id')) {
    unset($vals[user_id]);
    $user_id=$this->db->getval("SELECT user_id from $table where user_id=$GLOBALS[uid] and id=$id")*1;
    if ($user_id==0) {
        echo json_encode(['error'=>"No access"]);
        exit;
    }
}
//return $JSONData=['ok'=>'ok'];
$orig_vals=$this->data->get_row($table, $id);
//return $JSONData=$orig_vals;
$this->db->update_DB($table, $id, $vals);
$new_vals=$this->data->get_row($table, $id);

foreach ($vals as $key => $value) {
    $changed[$key]=['old'=>$orig_vals[$key],'new'=>$new_vals[$key]];
}

$JSONData=[
    'user_id'=>$GLOBALS[uid],
    'table'=>$table,
    'id'=>$id,
    'changed'=>$changed
];
