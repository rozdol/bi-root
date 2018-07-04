<?php
$id=$this->html->readRQn('id');
$parentid=$this->html->readRQn('parentid');
$refid=$this->html->readRQn('refid');
$value=$this->html->readRQ('value');
$pids=$this->html->readRQ('pids');
if ($pids=="") {
    $pids=0;
}
$ids=$this->html->readRQcsv('ids');
if ($ids=="") {
    $ids=0;
}
$field=$this->html->readRQ('field');
$fieldname=$this->html->readRQ('fieldname');
$table=$this->html->readRQ('fromtable');
$name=$this->html->readRQ('name');
$allowed = array('partners', 'accounts', 'services', 'a_accounts','contracts','plans','lots','piles','transport','projects','clients','assesment_items','uploads','transactions','trustsw','loans','entities','drugs','deals','deal_volumes','deal_ports','clientrequests','invoices');
if ($table!='') {
    $table=str_ireplace("user", "", $table);
    $table=str_ireplace("group", "", $table);
    $table=str_ireplace("access", "", $table);
    //$table=str_ireplace("services","",$table);
    if ((in_array($table, $allowed))||(($access[main_admin]))) {
    } else {
        echo "Hack attempt on AJAX is logged. $table not allowed";
        $warning_text="HACK on AJAX append procedure! $table not allowed";
        $this->data->DB_log($warning_text);
        $this->comm->mail2admin("Error in system", $warning_text);
        exit;
    }
}
if ($field<>'') {
    $field=str_ireplace("password", "", $field);
    $field=str_ireplace("access", "", $field);
}
$incl_file=APP_DIR.'/procedures/append/_default.php';
if (file_exists($incl_file)) {
    require(APP_DIR.'/procedures/append/_default.php');
}
$body.=$out;
