<?php
$out.=$this->html->tablefoot($i, $totals, $totalrecs);
if ($dynamic>0) {
    $nav=$this->html->HT_ajaxpager($totalrecs, $orgqry, "$titleorig.");
} else {
    $nav=$this->html->HT_pager($totalrecs, $orgqry);
}
if (($i>5)&&($this->html->readRQn('nocart')==0)) {
    $nav.= $this->html->add_all_to_cart2($what);
}
$csv=implode("\n", $csv_arr);
if ($noexport == 0) {
    if (($csv=='')&&($csv_arr)) {
        $csv=$this->utils->array_to_csv($csv_arr);
    }
    $export=$this->utils->exportcsv($csv);
}
$body.="$js $out $nav $export $extra";