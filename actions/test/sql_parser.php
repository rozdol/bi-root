<?php
require_once FW_DIR.DS.'classes'.DS.'PHPSQLParser'.DS.'PHPSQLParser.php';
//$parser = new PHPSQLParser();
$sql = 'SELECT 1';
echo $sql . "\n";
$start = microtime(true);
$parser = new PHPSQLParser($sql, true);
$stop = microtime(true);
print_r($parser->parsed);
echo "parse time simplest query:" . ($stop - $start) . "\n";
