<?php

$res2=$this->db->GetRow("select * from $what where id=$refid");
if ($act=='edit') {
    $sql="select * from $what WHERE id=$id";
    $res=$this->utils->escape($this->db->GetRow($sql));
} else {
}

$out.= "<div id='stylized' class='well'>


<form id='form1' name='form1' method='post' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post'>
	<h1>$action Currency Rates</h1>
	<p>Manage Rates</p>   
	<input type='hidden' name='debug' value='0'>     
	<dt><label>Date</label><input name='date' value='$res[date]' data-datepicker='datepicker' class='date' type='text' placeholder='DD.MM.YYYY'/></dt>
	";
    $sql="select * from listitems where list_id=6 order by id";
if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
while ($row = pg_fetch_array($cur)) {
    $out.= "<dt><label>$row[name]</label><input type='text' name='rate-$row[id]' value='$value'></dt>";
}

    $out.="
	<div class='spacer'></div>
	".$this->html->form_confirmations()."
	<button type='submit' name='act' value='save' id='button' class='btn btn-primary'  onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'>Save</button><br>
	<div class='spacer'></div>
</form>
</div>
";
    //$out.= "$sql";


$body.=$out;
