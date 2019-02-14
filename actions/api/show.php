<?php
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
    echo json_encode([
        'error'=>"No access for $table for $GLOBALS[username]",
      //  'access'=>$GLOBALS[access]
    ]
    );
    exit;
}

$q = QB::table($table);

$fields=$this->html->readRQcsv('fields', '', 0, 0);

if(!empty($fields)){
    $q = $q->select($fields);
}

$filters=$this->html->readRQcsv('filters', '', 0, 0);
foreach ($filters as $filter) {
    $f++;
    $filter=explode(' ', $filter);
    $count=count($filter);
    $count2=$count-3;
    $ors=$count2/4;

    if ($count==3) {
        $q = $q->where($filter[0], $filter[1], $filter[2]);
    }
    if (($count>3)&&($count2 % 4 == 0)) {
        $q = $q->where(function ($q2) use ($filter, $ors) {
            $q2->where($filter[0], $filter[1], $filter[2]);
                // You can provide a closure on these wheres too, to nest further.
            for ($i=1; $i <=$ors; $i++) {
                $q2->orWhere($filter[$i*4], $filter[$i*4+1], $filter[$i*4+2]);
            }
        });
    }
}


if ($this->data->field_exists($table, 'user_id')) {
    $q = $q->where('user_id', '=', $GLOBALS['uid']);
}

$rows=$q->get();

$queryObj = $q->getQuery();
$sql=$queryObj->getRawSql();
$this->html->dd($sql,1);
$result=$this->db->GetResults($sql);
//$JSONData['sql']=$sql;
$JSONData[table]=$table;
$JSONData[fields]=$fields;
$JSONData[rows]=$rows;
return $JSONData;

//return $JSONData=$result;
