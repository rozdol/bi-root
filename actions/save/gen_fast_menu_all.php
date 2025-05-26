<?php
echo $this->html->message("Generating Menu...");
$sql="select * from groups where id>=2 order by id asc limit 100";
if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
$rows=pg_num_rows($cur);
while ($row = pg_fetch_array($cur)) {
	$i++;
	$this->data->gen_fast_menu($row['id']);
	//$this->livestatus("Generating Menu for GID:$row[id]");
	echo $this->html->message("Generating Menu for GID:$row[id]");
	$this->livestatus(str_replace("\"","'",$this->html->draw_progress($i/$rows*100)));
}
$this->livestatus('');

//$out.= "<pre>";print_r($_POST);$out.= "</pre>";$out.= "<pre>";print_r($vals);$out.= "</pre>";exit;


$body.=$out;
