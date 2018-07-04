<?php
if ($what == 'accessitems'){
		//$_POST[subtype]="$id";
		//$_POST[reffinfo]="&tablename=$what&refid=$id";
		$name=$this->db->GetVal("select name from accessitems where id=$id");
		$out.= $this->html->show_hide('Table_access', "?act=details&what=groupaccess&id=&type=&nowrap=1&filt=$name","btn-mini btn-danger");
		//$out.=$this->show('types');
			
	}
	if($access[main_admin])$out.= $this->html->show_hide("$what alterations", "?act=show&what=dbchanges&plain=1&nomaxid=1&refid=$id&tablename=$what");

	
$body.=$out;
