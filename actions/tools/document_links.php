<?php
//tools document_links
/*
$rows=2000;
$fract=1;
$sleep=10000;
$GLOBALS[progress_delay]=1;
$start_time=$this->utils->get_microtime();
for($i=0; $i<=$rows; $i++){
    $this->progress($start_time, $rows, $i, $i);
    usleep($sleep);
}
--select count(*) from docs2partners where docid not in (select doc_id from docs2obj where ref_table='partners' and partnerid in (select partnerid from docs2partners));
--select count(*) from docs2partners;
--select count(*) from docs2obj where ref_table='partners'
--delete from docs2obj where ref_table='partners'
*/
$limit=$this->html->readRQn('limit');
if ($limit==0) {
    $limit=1000;
}
if ($access['main_admin']) {
    $sql="select * from docs2partners where docid not in (select doc_id from docs2obj where ref_table='partners' and partnerid in (select partnerid from docs2partners)) limit $limit";
    $this->livestatus("Preparing query....<br>");
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $rows=pg_num_rows($cur);
    $out.= "Rows: $rows<br>";
    $this->livestatus("Rows: $rows<br>");
    $start_time=$this->utils->get_microtime();
    while ($row = pg_fetch_array($cur)) {
        $i++;
        $this->progress($start_time, $rows, $i, "DID:$row[docid],PID:$row[partnerid],ID=$id");
        $vals=array(
            'doc_id'=>$row[docid],
            'ref_id'=>$row[partnerid],
            'ref_table'=>'partners',
            'type_id'=>0,
        );
        $id=$this->db->insert_db('docs2obj', $vals);
    }
    $this->livestatus('');
    //exit;
}
$body.=$out;
