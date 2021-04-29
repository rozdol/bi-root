<?php
if ($what == 'listitem2obj'){
	$ref_table=$this->html->readRQ('ref_table');
	$ref_id=$this->html->readRQn('ref_id');
	$listitem_id=$this->html->readRQn('listitem_id');
	$list_id=$this->html->readRQn('list_id');
	$list=$this->data->get_name('lists',$list_id);
	if($listitem_id==0){
		$res=$this->db->GetRow("select * from $ref_table where id=$ref_id");
		$name=$res[name];
		if($ref_table=='transactions'){
			$sql="SELECT distinct d.id, d.name||' '||substr(d.descr,0,40) FROM documents d, docs2partners p 
			WHERE p.docid=d.id and (p.partnerid=$res[sender] or p.partnerid=$res[receiver]) and d.id not in (select doc_id from docs2obj where ref_table='transactions' and ref_id=$id) and d.datefrom<='$res[date]' and d.dateto>='$res[date]'  ORDER by d.id";
			$type=$this->html->htlist('doc_id_list',$sql,0,'Select Document','','','span12');

			$doc_input="
			<dt><label>or choose</label>$type</dt>
			";
		}
		$out.=  "<h3>Join $list to $ref_table $name</h3>\n";
		$sql="SELECT id, name from listitems where list_id=$list_id order by id";
		$itemlist=$this->html->htlist('listitem_id',$sql,0,'Select Item','','','span12');

		$out.= "
			<form class='well' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='$what'>
			<input type='hidden' name='ref_id' value='$ref_id'>
			<input type='hidden' name='ref_table' value='$ref_table'>
			<table class='m'>
		<dt><label>$list</label>$itemlist</dt>
		<tr><td class='mr'> </td><td class='m'><input type='submit' value='save' class='btn btn-info'> </td></tr>
		</table></form> ";
	}else{
		$res=$this->db->GetRow("select * from listitems where id=$listitem_id");
		$name=$res[name];

		$out.=  "<div class='title2'>Join document $name to $ref_table </div>\n";
			$out.= "<form class='well' action='?csrf=$GLOBALS[csrf]&act=save&what=$what' method='post' name='$what'>
			<input type='hidden' name='listitem_id' value='$doc_id'>
			<input type='hidden' name='ref_table' value='$ref_table'>
			<table class='m'>
		   	<dt><label>$ref_table ID</label><input type='text' name='ref_id'  id='name_id' value=''></dt>
		   <tr><td class='mr'> </td><td class='m'><input type='submit' value='save' class='btn btn-info'> </td></tr>
		</table></form>";
	}

	//$out.= "$sql";
	}
$body.=$out;
