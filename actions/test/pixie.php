<?php
// composer requires usmanhalalit/pixie
// https://github.com/usmanhalalit/pixie
$table='entities';
$id=3;
$query = QB::table($table)
    ->select('user_id')
    ->where('user_id', $GLOBALS[uid])
    ->where('id', $id);
$user_id = $query->first();
echo $this->html->pre_display($user_id, "user_id");

$q = QB::table('entities');
$q = $q->select(['id','name']);

$filters=$this->html->readRQcsv('filters', '', 0, 0);
foreach ($filters as $filter) {
    $f++;
    $filter=explode(' ', $filter);
    $count=count($filter);
    $count2=$count-3;
    $ors=$count2/4;
    $JSONData['count1/'.$f]=$count;
    $JSONData['count2/'.$f]=$count2;
    $JSONData['ors'.$f]=$ors;
    $JSONData['filter'.$f]=$filter;

    echo "ORs($f):$ors<br>";
    if ($count==3) {
        $q = $q->where($filter[0], $filter[1], $filter[2]);
    }
    if (($count>3)&&($count2 % 4 == 0)) {
        $q = $q->where(function ($q2) use ($filter, $ors) {
            $q2->where($filter[0], $filter[1], $filter[2]);
            echo "First Q<br>";
                // You can provide a closure on these wheres too, to nest further.
            for ($i=1; $i <= $ors; $i++) {
                echo "Q$i<br>";
                $q2->orWhere($filter[$i*4], $filter[$i*4+1], $filter[$i*4+2]);
            }
        });

        // $q = $q->where($filter[0], $filter[1], $filter[2]);
        // $q = $q->orWhere($filter[4], $filter[5], $filter[6]);
    }
}

// $q = $q->where('id', '>=', "0");
// $q = $q->where('active', '=', "1");
// $count2=4;
// if ($count2 % 4 ==0) {
//     $name='alex';
//     $q = $q->where(function ($q2) use ($name) {
//             $q2->where('username', '=', $name);
//             // You can provide a closure on these wheres too, to nest further.
//             $q2->orWhere('surname', 'LIKE', '%alex%');
//     });
// }





$q = $q->orderBy('id', 'DESC');
$rows=$q->get();
foreach ($rows as $row) {
    //echo $this->html->pre_display($row, "row ".$row->username);
}

//echo $this->html->pre_display($rows, "result");
echo $this->html->array_display($rows);
$json=json_encode($rows, JSON_PRETTY_PRINT);
//echo $this->html->pre_display($json, "json");

$queryObj = $q->getQuery();
$sql=$queryObj->getRawSql();
echo $this->html->pre_display($sql, "sql");
