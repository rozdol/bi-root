<?php
if ($what == 'import_lists') {
    $data=$this->html->readRQc('data');
    $data=str_ireplace("\t", ";", $data);
    $data=str_ireplace("\n", "", $data);
    $records=explode("\r", $data);
    $list_id=$this->html->readRQn('refid');
    //$out.= pre_display($records);
    foreach ($records as $rec) {
        //$out.= "$rec<br>";
        $parts=explode(";", $rec);
        //$out.= "$parts[1]---<br>";
        if (strlen($parts[1])>0) {
            //$out.= "$parts[1]---====<br>";
            $name=$parts[0];
            $alias=$parts[1];
            $qty=$this->utils->cleannumber($parts[2]);
            $values=$parts[3];
            $text1=$parts[4];
            $text2=$parts[5];
            $num1=($parts[6])*1;
            $num2=($parts[7])*1;
            $descr=$parts[8];
            $addinfo=$parts[9];
            
            $vals=array(
                'name'=>$name,
                'alias'=>$alias,
                'list_id'=>$list_id,
                'qty'=>$qty,
                'values'=>$values,
                'descr'=>$descr,
                'addinfo'=>$addinfo,
                'text1'=>$text1,
                'text2'=>$text2,
                'num1'=>$num1,
                'num2'=>$num2
            );
            //$out.= "<pre>";print_r($vals);$out.= "</pre>";
             //$out.= pre_display($vals,$vals[alias]);//exit;
            $id=$this->db->insert_db('listitems', $vals);
        }
    }
    //exit;
}

$body.=$out;
