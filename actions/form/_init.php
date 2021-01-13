<?php
//form init

$tablename=$this->html->readRQ('tablename');
$reference=$this->html->readRQ('reference');
$refid=$this->html->readRQn('refid');
$back_to_url=$this->html->readRQn('back_to_url');
if($id==0)$action="Add"; else $action="Edit";
$raw_data=$this->html->readRQn('raw_data');
if($raw_data>0){
	if (!$access['main_admin']) {
	    $this->html->error('Honey pot');
	}
	$tablename=$what;
	if (!$this->data->table_exists($what))$this->html->error($this->html->message("Object '$what' not found.", "404", 'alert-error'));
	if($id==0)$this->html->error('No ID');
	if ($this->data->field_exists($what, 'id')) {
	    $sql="select * from $what WHERE id=$id";
	    $res=$this->utils->escape($this->db->GetRow($sql));
	}
	$name=$this->data->detalize($tablename,$id);
	$sql="SELECT * FROM $tablename";
	//echo $sql;
	if (!($cur = pg_query($sql))) {
	    $this->html->SQL_error($sql);
	}
	echo $this->html->tag("Raw edit $tablename: $name",'h3','');
	$i = pg_num_fields($cur);
	$tbl.= "<table class='table table-bordered table-morecondensed table-notfull'>";
	for ($j = 0; $j < $i; $j++) {
		$fieldname = pg_field_name($cur, $j);
		$fieldtype = pg_field_type($cur, $j);
		$value=$res[$fieldname];
		if($fieldname!='id'){
			//echo "$fieldname:$fieldtype=".$res[$fieldname]."<br>";
			    $submitdata=array(
			        'table'=>$tablename,
			        'field'=>$fieldname,
			        'id'=>$id,
			    );
			    $class="#${tablename}_${fieldname}";
			    $js1.=$this->utils->inline_js($class, $submitdata);
			    $value= "<span class='' id='${tablename}_${fieldname}'>$value</span>";
			$tbl.= "<tr>";
			$tbl.= "<td class='bold'>$fieldname</td>";
			$tbl.= "<td>$value</td>";
			$tbl.= "</tr>";
		}


	}
    $js="
		<script>
	$(document).ready(function() {
		$js1
		});
		</script>";
	$tbl.= "</table>";
	echo $tbl.$js;
	exit;
}

