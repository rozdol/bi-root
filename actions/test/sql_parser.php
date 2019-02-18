<?php
$query1 = "select * from a where id>0 and date<='01.01.2019' and type_id in (1,2,3) and (descr like '%tmp%' or descr like '%var%' )";
$query1 = "
		   select id, name from listitems where list_id=6 and (id=600 or id=602);";
$parser = new PhpMyAdmin\SqlParser\Parser($query1);

// inspect query
 // outputs object(PhpMyAdmin\SqlParser\Statements\SelectStatement)



 $table=$parser->statements[0]->from[0]->table;

  //echo $this->html->cols2($this->html->pre_display($parser->statements[0],"1"),$this->html->pre_display($parser->statements[0],"$table"));

 $q = QB::table($table);

 $fields=$this->html->readRQcsv('fields', '', 0, 0);
 foreach ($parser->statements[0]->expr as $key => $value) {
 	//echo "$value<br>";
 	$fields[]=$value->column;
 }
 //$fields=$parser->statements[0]->expr[0]->table;

//echo $this->html->pre_display($fields,"fields");


 if(!empty($fields)){
     $q = $q->select($fields);
 }

 //$_POST[filters]='id = 1,id = 2 or id = 3 or id = 4, id = 5, id > 9';

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

 $_POST[ids]='1,2,34';
 $ids=$this->html->readRQcsv('ids', '', 0, 0);

 if(!empty($ids)){
     $q = $q->whereIn('id', $ids);
 }

 echo $this->html->pre_display($ids,"result");
 $rows=$q->get();

 $queryObj = $q->getQuery();
 $sql=$queryObj->getRawSql();
 $this->html->dd($sql,1);



// // modify query by replacing table a with table b
// $table2 = new \PhpMyAdmin\SqlParser\Components\Expression("", "b", "", "");
// $parser->statements[0]->from[0] = $table2;

// // build query again from an array of object(PhpMyAdmin\SqlParser\Statements\SelectStatement) to a string
// $statement = $parser->statements[0];
// $query2 = $statement->build();
// echo $this->html->pre_display($query2,"result"); // outputs string(19) "SELECT  * FROM `b` "

// // Change SQL mode
// PhpMyAdmin\SqlParser\Context::setMode('ANSI_QUOTES');

// // build the query again using different quotes
// $query2 = $statement->build();

// echo $this->html->pre_display($query2,"result"); // outputs string(19) "SELECT  * FROM "b" "


