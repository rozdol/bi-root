<?php
if ($what == 'versions'){
		$res=$this->db->GetRow("select * from $what where id=$id");
		$out.="<h1>$res[name]</h1>\n";
		$res[descr]=str_ireplace("\n","<li>",$res[descr]);
		$res[descr]=str_ireplace(":",":<br>-",$res[descr]);
		$res[descr]=str_ireplace(",","<br>-",$res[descr]);
		$out.= "<pre>$res[descr]</pre>";
	}
	
$body.=$out;
