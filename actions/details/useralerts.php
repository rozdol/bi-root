<?php
if ($what == 'useralerts') {
        $sql = "SELECT * FROM $what WHERE id=$id";
        $res=$this->utils->escape($this->db->GetRow($sql));
    if ($uid==$res[userid]) {
        $dummy=$this->db->GetVal("update $what set readdate=now(), readtime=now(), wasread='1' where id=$id and confirm='0'");
    }
        $link="?act=details&what=$res[tablename]&id=$res[refid]";
        //$out.= "$link         $sql";
        header("Location: $link");
}
    
$body.=$out;
