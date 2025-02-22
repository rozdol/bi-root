<?php
if ($sqltotal!='') {
    $totalrecs=$this->db->GetVal("select count(*)".$sqltotal)*1;
}
$out.=$this->html->tablefoot($i, $totals, $totalrecs);
//$titleorig='Transactions_All';
if ($dynamic>0) {
    $nav=$this->html->HT_ajaxpager($totalrecs, $orgqry, "$titleorig.");
} else {
    $nav=$this->html->HT_pager($totalrecs, $orgqry);
}
//if($dynamic>0)$nav=$this->html->HT_ajaxpager($totalrecs,$orgqry,"test_test");else $nav=$this->html->HT_pager($totalrecs,$orgqry);
//if(($i>5)&&($this->html->readRQn('nocart')==0))$nav.= $this->html->add_all_to_cart2($what);
if (($i>5)&&($this->html->readRQn('nocart')==0)) {
    $nav.= $this->html->add_all_to_cart2($what);
}
if ($noexport == 0) {
    if (($csv=='')&&($csv_arr)) {
        $csv=$this->utils->array_to_csv($csv_arr);
        $JSONData=$csv_arr;
    }elseif($csv!=''){
    	$csv_arr=$this->utils->csv_to_array($csv);
    	$JSONData=$csv_arr;
    }
    $export=$this->utils->exportcsv($csv);
}
$body.="$js $out $nav $export $extra";
