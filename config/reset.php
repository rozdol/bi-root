<?php
$this->db->__destruct();
// $this->db->getval("SELECT 1");
// exit;
$default_conn = @pg_connect("host=".$GLOBALS['DB']['DB_SERVER']."
							 port=".$GLOBALS['DB']['DB_PORT']."
							 dbname='postgres'  
							 user=".$GLOBALS['DB']['DB_USER']."
							 password=".$GLOBALS['DB']['DB_PASS']);
if ((!$default_conn)) {
    return $this->html->error("No connection to one of the DBs");
}
$sql_disconnect="SELECT pg_terminate_backend(pg_stat_activity.pid)
    FROM pg_stat_activity
    WHERE pg_stat_activity.datname = '".$GLOBALS['DB']['DB_NAME']."'
      AND pid <> pg_backend_pid();";
if (!($cursor = pg_query($default_conn, $sql_disconnect))) {
    $this->html->SQL_error($sql_disconnect);
}

$sql_reset="DROP DATABASE ".$GLOBALS['DB']['DB_NAME'].";";
if (!($cursor = pg_query($default_conn, $sql_reset))) {
    $this->html->SQL_error($sql_reset);
}

$sql_reset="CREATE DATABASE ".$GLOBALS['DB']['DB_NAME']."
  WITH OWNER = ".$GLOBALS['DB']['DB_USER']."
       ENCODING = 'UTF8'
       LC_COLLATE = 'en_US.UTF-8'
       LC_CTYPE = 'en_US.UTF-8'
       CONNECTION LIMIT = -1;";
if (!($cursor = pg_query($default_conn, $sql_reset))) {
    $this->html->SQL_error($sql_reset);
}

echo $this->html->refreshpage('?act=welcome', 1, $this->html->message("Database ".$GLOBALS['DB']['DB_NAME']." has been reset", "$act $what", 'alert-warn'));
exit;
