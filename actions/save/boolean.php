<?php
//save boolean
$ref_id = $this->html->readRQn('ref_id');
$field = $this->html->readRQ('field');
$ref_table = $this->html->readRQ('ref_table');

$parent_table = $this->html->readRQ('parent_table');
$parent_id = $this->html->readRQn('parent_id');

$allowed = array('partners', 'accounts', 'services', 'a_accounts', 'contracts', 'plans', 'lots', 'piles', 'transport', 'projects', 'clients', 'assesment_items', 'uploads', 'transactions', 'trustsw', 'loans', 'services2requests', 'services2orders', '');
if ($ref_table <> '') {
	$ref_table = str_ireplace("user", "", $ref_table);
	$ref_table = str_ireplace("group", "", $ref_table);
	$ref_table = str_ireplace("access", "", $ref_table);
	//$ref_table=str_ireplace("services","",$table);
	if ((in_array($ref_table, $allowed)) || (($access['main_admin']) || (1 == 1))) {
	} else {
		echo "Hack attempt on BOLLEAN_FUNC is logged. $ref_table not allowed";
		$this->data->DB_log("HACK on BOLLEAN_FUNC procedure! $ref_table not allowed");
		exit;
	}
}

if ($field <> '') {
	$field = str_ireplace("password", "", $field);
	$field = str_ireplace("access", "", $field);
}
if ($ref_table == 'documents') {
	$sql = "update documentactions set complete='1' where docid=$ref_id;";
	$err = $this->db->GetVal($sql);
	if ($err != '') {
		$out .= $err;
		exit;
	}
}
if ($ref_table == 'deal_routes') {
	$val = $this->data->get_val($ref_table, $field, $ref_id);
	if ($val == 'f') $err = $this->db->getval("UPDATE deal_routes set main='f' where deal_volume_id=$parent_id and id!=$ref_id");
	//echo "$val , $parent_id , $ref_id<br>"; exit;
	if ($err != '') {
		$out .= $err;
		exit;
	}
}
$type = $this->data->field_type($ref_table, $field);
if ($type == 'bool') {
	$sql = "update $ref_table set $field = not $field where id=$ref_id;";
} else {
	$val = $this->data->get_val($ref_table, $field, $ref_id);
	if ($val != 0) $val = 0;
	else $val = 1;
	$sql = "update $ref_table set $field = $val where id=$ref_id;";
}
$err = $this->db->GetVal($sql);
//$err=$sql;
if ($err != '') {
	$out .= $err;
	exit;
}
$body .= $out;
