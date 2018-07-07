<?php
$this->db->__destruct();
// $this->db->getval("SELECT 1");
// exit;
$default_conn = @pg_connect("host=".$_ENV['DB_SERVER']."
							 port=".$_ENV['DB_PORT']."
							 dbname='postgres'  
							 user=".$_ENV['DB_USER']."
							 password=".$_ENV['DB_PASS']);
if ((!$default_conn)) {
    return $this->html->error("No connection to one of the DBs");
}
$sql_disconnect="SELECT pg_terminate_backend(pg_stat_activity.pid)
    FROM pg_stat_activity
    WHERE pg_stat_activity.datname = '".$_ENV['DB_NAME']."'
      AND pid <> pg_backend_pid();";
if (!($cursor = pg_query($default_conn, $sql_disconnect))) {
    $this->html->SQL_error($sql_disconnect);
}

$sql_reset="DROP DATABASE ".$_ENV['DB_NAME'].";";
if (!($cursor = pg_query($default_conn, $sql_reset))) {
    $this->html->SQL_error($sql_reset);
}

$sql_reset="CREATE DATABASE ".$_ENV['DB_NAME']."
  WITH OWNER = postgres
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'en_US.UTF-8'
       LC_CTYPE = 'en_US.UTF-8'
       CONNECTION LIMIT = -1;";
if (!($cursor = pg_query($default_conn, $sql_reset))) {
    $this->html->SQL_error($sql_reset);
}

echo $this->html->refreshpage('?act=welcome', 10, $this->html->message("Database ".$_ENV['DB_NAME']." has been reset", "$act $what", 'alert-warn'));
exit;
