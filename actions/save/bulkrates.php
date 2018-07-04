<?php
if ($what == 'bulkrates') {
    $rows=0;
    $success=1;
    $data=$this->html->readRQc('data');
    $currency=$this->html->readRQ('currency');
    //$data=str_ireplace("\t", ";",$data);
    $records=explode("\r", $data);
    foreach ($records as $rec) {
        $parts=explode(",", $rec);
        $parts[0]=str_ireplace("\n", "", $parts[0]);
        $parts[0]=str_ireplace("\t", "", $parts[0]);
        $parts[0]=str_ireplace(",", "", $parts[0]);
        if (strlen($parts[0])>0) {
            $rate=$parts[1]*1;
            $date=$this->dates->F_MDconvdate($parts[0]);
            $out.= "$currency - $date - $rate<br>";
            $this->db->GetVal("delete from rates where currency=$currency and date='$date'");
            $this->db->GetVal("insert into rates (currency,date,rate) values ($currency,'$date', $rate)");
        }
    }
    //$out.= "<textarea name='descr' >$out</textarea>";
}
//exit;



$body.=$out;
