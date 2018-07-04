<?php
if ($what == 'schedules') {
        $sql = "SELECT * FROM $what WHERE id=$id";
        $res=$this->utils->escape($this->db->GetRow($sql));
        $link="?act=details&what=$res[tablename]&id=$res[refid]";
        //$out.= "$link         $sql";
        header("Location: $link");
}
    
    
    
$body.=$out;
