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

    //$_POST[filters]='id = 1,id = 2 or id = 3 or id = 4, id = 5, id > 9';
}

$table=$this->html->readRQs('table');

if (!$table) {
    echo json_encode(['error'=>'No `table` defined']);
    exit;
}

if (!$GLOBALS["access"]["view_$table"]) {
    echo json_encode([
        'error'=>"No access to show '$table' for user '$GLOBALS[username]'",
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
$q = $q->where('id', '>', 0);

$limit=$this->html->readRQn('limit');
if($limit>0) $q = $q->limit($limit);

$page=$this->html->readRQn('page');
if($page>0) $q = $q->offset($page);

$sortby=$this->html->readRQ('sortby');
if($sortby!='') $q = $q->orderBy($sortby, 'ASC');

$sortby_=$this->html->readRQ('sortby_');
if($sortby_!='') $q = $q->orderBy($sortby_, 'DESC');

$rows=$q->get();
$JSONData[table]=$table;
$JSONData[fields]=$fields;
foreach ($fields as $field) {
    $width=100;
    if($field=='name')$width=200;
    if($field=='descr')$width=400;
    if($field=='id')$width=50;
    $JSONData[config][]=[ "id"=>$field, "header"=>$field, "width"=>$width];
}

$JSONData[rows]=$rows;

// $queryObj = $q->getQuery();
// $sql=$queryObj->getRawSql();
//$this->html->dd($sql,1);
//$result=$this->db->GetResults($sql);
//$JSONData['sql']=$sql;
// $JSONData[table]=$table;
// $JSONData[fields]=$fields;
//$JSONData=$rows;
//return $JSONData;

//return $JSONData=$result;
