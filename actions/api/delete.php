<?php
$help=$this->html->readRQn('help');
if ($help) {
    $func=$this->html->readRQs('func');
    $arr=[
        "user"=> "admin",
            "api_key"=> "dff1d9-1ef5e0-f37396-701ffc-6deff8",
            "func"=> "delte",
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


if ($this->data->field_exists($table, 'user_id')) {
    unset($vals[user_id]);
    $query = QB::table($table)
        ->select('user_id')
        ->where('user_id', $GLOBALS[uid])
        ->where('id', $id);
    $user_id = $query->first()->user_id*1;


    if ($user_id==0) {
        echo json_encode(['error'=>"No access"]);
        exit;
    }
}

$orig_vals=$this->data->get_row($table, $id);

$this->delete($table, $id);
//$this->db->delete_DB($table, $id);


$JSONData=[
    'user_id'=>$GLOBALS[uid],
    'table'=>$table,
    'id'=>$id,
    'deleted'=>"$table:$id"
];
