<?php
// FW DB initialization
echo $this->html->refreshpage('?act=welcome', 1, $this->html->message("Database ".$GLOBALS['DB']['DB_NAME']." has been initialized", "$act $what", 'alert-warn'));
echo "<hr>";
echo "Creating users...<br>";
$sql_files[]=FW_DIR.DS.'config'.DS.'setup.sql';
$sql_files[]=APP_DIR.DS.'config'.DS.'setup.sql';
$sql_files[]=APP_DIR.DS.'config'.DS.'final.sql';
$sql_files[]=FW_DIR.DS.'config'.DS.'final.sql';
foreach ($sql_files as $sql_file) {
    echo "Trying file: $sql_file<br>";
    if (file_exists($sql_file)) {
        $sql = file_get_contents($sql_file);
        //echo $this->html->pre_display($sql,$sql_file)."<hr>";
        //@pg_query($this->conn, $sql);
        $out=$this->db->Query($sql);
        echo "out:$out<hr>";
    }
}
$init_info="time:".date("d.m.y G:i")." site:$GLOBALS[siteURL], ip:$_SERVER[REMOTE_ADDR]";
$this->db->getval("UPDATE users set ga='$init_info' where id=-1");
echo $this->html->refreshpage('?act=welcome', 1, $this->html->message("Database ".$GLOBALS['DB']['DB_NAME']." has been initialized", "$act $what", 'alert-warn'));
