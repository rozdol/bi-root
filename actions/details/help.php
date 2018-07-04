<?php
if ($what == 'help'){
		$res=$this->db->GetRow("select * from $what where id=$id");
		$out.="<h1>$res[name]</h1>\n";
		$res[descr]=str_ireplace("\n","<br>",$res[descr]);
		$out.= "$res[descr]";
	}
	
$body.=$out;
